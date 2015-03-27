<?php namespace NewUp\Templates\Generators;

class AuthorsGenerator {

    public static function generate(array $authors)
    {
        $newAuthors = [];

        foreach ($authors as $author)
        {
            $newAuthors[] = (object)$author;
        }

        return $newAuthors;
    }

}