<?php
declare(strict_types=1);

/** Server-side validation helpers. */
class Validator
{
    /** Validates and normalizes a note payload. Returns [data, errors]. */
    public static function note(array $input): array
    {
        $data = [
            'title' => trim((string)($input['title'] ?? '')),
            'type' => (string)($input['type'] ?? 'general'),
            'description' => trim((string)($input['description'] ?? '')),
            'content' => trim((string)($input['content'] ?? '')),
            'is_favorite' => isset($input['is_favorite']) ? 1 : 0,
            'is_public' => isset($input['is_public']) ? 1 : 0,
            'location' => trim((string)($input['location'] ?? '')),
            'place_type' => trim((string)($input['place_type'] ?? '')),
            'gender' => trim((string)($input['gender'] ?? '')),
            'age' => trim((string)($input['age'] ?? '')),
            'origin' => trim((string)($input['origin'] ?? '')),
            'event_date' => trim((string)($input['event_date'] ?? '')),
            'participants' => trim((string)($input['participants'] ?? ''))
        ];
        $errors = [];
        if ($data['title'] === '' || mb_strlen($data['title']) > 150) $errors[] = 'Название обязательно и не должно превышать 150 символов.';
        if (!in_array($data['type'], ['general', 'place', 'character', 'event'], true)) $errors[] = 'Выбран неизвестный тип заметки.';
        if ($data['content'] === '') $errors[] = 'Основное содержание обязательно.';
        if (mb_strlen($data['description']) > 500) $errors[] = 'Описание не должно превышать 500 символов.';
        if ($data['type'] === 'character' && $data['age'] !== '' && (!ctype_digit($data['age']) || (int)$data['age'] > 1000)) $errors[] = 'Возраст должен быть целым числом от 0 до 1000.';
        if ($data['type'] === 'event' && $data['event_date'] !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['event_date'])) $errors[] = 'Некорректный формат даты события.';
        return [$data, $errors];
    }

    /** Escapes a value for safe HTML output. */
    public static function e(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
