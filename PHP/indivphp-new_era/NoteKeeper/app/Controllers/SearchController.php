<?php
declare(strict_types=1);

/** Handles note searching. */
class SearchController
{
    /** Displays search form and matching notes. */
    public function index(): void
    {
        Auth::requireLogin();
        $q=trim((string)($_GET['q']??''));
        $type=(string)($_GET['type']??'');
        $date=(string)($_GET['date']??'');
        $notes=Note::search((int)Auth::user()['id'],$q,$type,$date);
        render('search/index',['title'=>'Поиск заметок','notes'=>$notes,'q'=>$q,'type'=>$type,'date'=>$date]);
    }
}
