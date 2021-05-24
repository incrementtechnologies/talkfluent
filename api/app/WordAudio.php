<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WordAudio extends APIModel
{
    protected $table = 'word_audios';
    protected $fillable = ['word', 'plain', 'audio', 'translation',  'path', 'text_accent', 'transcription', 'classifications', 'syllabus', 'syllabus_audio', 'pronounciation', 'pronounciation_audio'];
}
