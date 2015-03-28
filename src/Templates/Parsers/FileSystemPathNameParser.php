<?php namespace NewUp\Templates\Parsers;

use NewUp\Contracts\Templates\PathNameParser;
use NewUp\Contracts\Templates\Renderer;

class FileSystemPathNameParser implements PathNameParser {

    const ESCAPE_OPEN_CURLY_BRACKET  = '//ESCAPE_OPEN_CURLY_BRACKET//';

    const ESCAPE_CLOSE_CURLY_BRACKET = '//ESCAPE_CLOSE_CURLY_BRACKET//';

    /**
     * The Renderer instance.
     *
     * @var \NewUp\Contracts\Templates\Renderer
     */
    protected $templateRenderer;

    /**
     * The characters that should be removed from the final path.
     *
     * @var array
     */
    protected $charactersToRemove = [];

    public function __construct(Renderer $templateRenderer)
    {
        $this->templateRenderer = $templateRenderer;
    }

    /**
     * Adds characters that should be automatically removed from the final path.
     *
     * @param array $characters
     * @return mixed
     */
    public function addCharactersToRemove(array $characters)
    {
        $this->charactersToRemove = array_merge($this->charactersToRemove, $characters);
    }

    /**
     * Resets the list of characters that should automatically be removed from the final path.
     *
     * @return mixed
     */
    public function resetCharactersToRemove()
    {
        $this->charactersToRemove = [];
    }

    /**
     * Gets the characters that should be removed from the final path.
     *
     * @return mixed
     */
    public function getCharactersToRemove()
    {
        return $this->charactersToRemove;
    }

    /**
     * Removes the "remove" characters from the given string.
     *
     * @param $string
     * @return string
     */
    private function removeUnwantedCharactersFromString($string)
    {
        return str_replace($this->charactersToRemove, '', $string);
    }

    /**
     * Converts the sequence of double opening and closing curly brackets to the escape strings.
     *
     * @param $string
     * @return string
     */
    private function escapeOpenCurlyBracket($string)
    {
        $string = str_replace('{{', self::ESCAPE_OPEN_CURLY_BRACKET, $string);
        $string = str_replace('}}', self::ESCAPE_CLOSE_CURLY_BRACKET, $string);
        return $string;
    }

    /**
     * Converts the curly bracket escape strings into the single opening and closing curly bracket equivalents.
     *
     * @param $string
     * @return string
     */
    private function unEscapeOpenCurlyBracket($string)
    {
        $string = str_replace(self::ESCAPE_OPEN_CURLY_BRACKET, '{', $string);
        $string = str_replace(self::ESCAPE_CLOSE_CURLY_BRACKET, '}', $string);
        return $string;
    }

    /**
     * Processes the provided path.
     *
     * @param $path
     * @return mixed
     */
    public function processPath($path)
    {
        $path = $this->escapeOpenCurlyBracket($path);

        $path = $this->unEscapeOpenCurlyBracket($path);
        $path = $this->removeUnwantedCharactersFromString($path);
        return $path;
    }


}