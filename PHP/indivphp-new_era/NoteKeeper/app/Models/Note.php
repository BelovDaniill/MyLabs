<?php
declare(strict_types=1);

/** Database operations for notes and their type-specific details. */
class Note
{
    /** Returns notes belonging to a user, optionally filtered by type. */
    public static function forUser(int $userId, string $type = ''): array
    {
        $sql = 'SELECT * FROM notes WHERE user_id = ?';
        $params = [$userId];
        if (in_array($type, ['general','place','character','event'], true)) {
            $sql .= ' AND type = ?';
            $params[] = $type;
        }
        $sql .= ' ORDER BY updated_at DESC';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Finds one note by ID. */
    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM notes WHERE id = ?');
        $stmt->execute([$id]);
        $note = $stmt->fetch();
        if (!$note) return null;
        $table = ['place'=>'places', 'character'=>'characters', 'event'=>'events'][$note['type']] ?? null;
        if ($table) {
            $detail = Database::connection()->prepare("SELECT * FROM {$table} WHERE note_id = ?");
            $detail->execute([$id]);
            $note['details'] = $detail->fetch() ?: [];
        } else {
            $note['details'] = [];
        }
        return $note;
    }

    /** Creates a note and its optional type-specific record in one transaction. */
    public static function create(int $userId, array $data): void
    {
        $pdo = Database::connection();
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare('INSERT INTO notes (user_id,title,type,description,content,is_favorite,is_public) VALUES (?,?,?,?,?,?,?)');
            $stmt->execute([$userId,$data['title'],$data['type'],$data['description'],$data['content'],$data['is_favorite'],$data['is_public']]);
            $id = (int)$pdo->lastInsertId();
            self::saveDetails($pdo, $id, $data);
            $pdo->commit();
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    /** Updates a note and replaces its type-specific record. */
    public static function update(int $id, array $data): void
    {
        $pdo = Database::connection();
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare('UPDATE notes SET title=?,type=?,description=?,content=?,is_favorite=?,is_public=?,updated_at=CURRENT_TIMESTAMP WHERE id=?');
            $stmt->execute([$data['title'],$data['type'],$data['description'],$data['content'],$data['is_favorite'],$data['is_public'],$id]);
            foreach (['places','characters','events'] as $table) {
                $del = $pdo->prepare("DELETE FROM {$table} WHERE note_id=?");
                $del->execute([$id]);
            }
            self::saveDetails($pdo, $id, $data);
            $pdo->commit();
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    /** Inserts the subtype row corresponding to the note type. */
    private static function saveDetails(PDO $pdo, int $id, array $data): void
    {
        if ($data['type'] === 'place') {
            $s=$pdo->prepare('INSERT INTO places(note_id,location,place_type) VALUES(?,?,?)');
            $s->execute([$id,$data['location'],$data['place_type']]);
        } elseif ($data['type'] === 'character') {
            $s=$pdo->prepare('INSERT INTO characters(note_id,gender,age,origin) VALUES(?,?,?,?)');
            $s->execute([$id,$data['gender'],$data['age'] === '' ? null : (int)$data['age'],$data['origin']]);
        } elseif ($data['type'] === 'event') {
            $s=$pdo->prepare('INSERT INTO events(note_id,event_date,location,participants) VALUES(?,?,?,?)');
            $s->execute([$id,$data['event_date'] ?: null,$data['location'],$data['participants']]);
        }
    }

    /** Deletes a note. */
    public static function delete(int $id): void
    {
        $stmt=Database::connection()->prepare('DELETE FROM notes WHERE id=?');
        $stmt->execute([$id]);
    }

    /** Searches notes belonging to a user using bound parameters. */
    public static function search(int $userId, string $query, string $type, string $date): array
    {
        $sql='SELECT * FROM notes WHERE user_id=?';
        $params=[$userId];
        if ($query !== '') {
            $sql.=' AND (title LIKE ? OR description LIKE ? OR content LIKE ?)';
            $like='%'.$query.'%';
            array_push($params,$like,$like,$like);
        }
        if (in_array($type,['general','place','character','event'],true)) { $sql.=' AND type=?'; $params[]=$type; }
        if ($date !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/',$date)) { $sql.=' AND date(created_at)=?'; $params[]=$date; }
        $sql.=' ORDER BY updated_at DESC';
        $stmt=Database::connection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Returns public notes for the landing page. */
    public static function publicLatest(int $limit = 3): array
    {
        $stmt=Database::connection()->prepare('SELECT notes.*, users.username FROM notes JOIN users ON users.id=notes.user_id WHERE is_public=1 ORDER BY created_at DESC LIMIT ?');
        $stmt->bindValue(1,$limit,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** Returns every note for administrator management. */
    public static function all(): array
    {
        return Database::connection()->query('SELECT notes.*, users.username FROM notes JOIN users ON users.id=notes.user_id ORDER BY notes.updated_at DESC')->fetchAll();
    }

    /** Returns true if the note belongs to the specified user. */
    public static function belongsTo(int $noteId, int $userId): bool
    {
        $stmt=Database::connection()->prepare('SELECT COUNT(*) FROM notes WHERE id=? AND user_id=?');
        $stmt->execute([$noteId,$userId]);
        return (int)$stmt->fetchColumn() > 0;
    }
}
