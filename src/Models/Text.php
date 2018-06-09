<?php

namespace Models;

use DSI\Service\Translate;
use Illuminate\Database\Eloquent\Model;

class Text extends Model
{
    const TABLE = 'texts';
    protected $table = self::TABLE;
    protected $fillable = [
        self::Identifier,
        self::Lang,
    ];

    const Id = 'id';
    const Identifier = 'identifier';
    const Copy = 'copy';
    const Lang = 'lang';

    /** @return Text */
    public static function getByIdentifier($identifier, $lang = null)
    {
        if (!$lang)
            $lang = Translate::getCurrentLang();

        return self::firstOrCreate([
            self::Identifier => $identifier,
            self::Lang => $lang,
        ]);
    }

    public function getCopy()
    {
        return $this->{self::Copy};
    }
}