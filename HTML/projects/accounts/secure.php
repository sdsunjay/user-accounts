**
 * Helps to properly escape variables for different output types.
 *
 * @see https://www.janoszen.com/2012/04/16/proper-xss-protection-in-javascript-php-and-smarty/
 */
class EscapeHelper {
    /**
     * Escapes variables for HTML content. Does not support arrays.
     */
    const TARGET_HTML = 'html';
    /**
     * Escapes variables for javascript content.
     */
    const TARGET_JS   = 'javascript';
    /**
     * Escapes variables for URL's. Does not support arrays.
     */
    const TARGET_URL  = 'url';

    /**
     * Exception throw helper function.
     *
     * @param mixed  $variable
     * @param string $target 
     *
     * @throws Exception
     */
    protected static function typeError($variable, $target) {

        throw Exception('Variable of type ' . gettype($variable) .
            ' cannot be escaped for ' . $target);
    }

    /**
     * Escapes variables for different output targets.
     *
     * @param mixed  $variable
     * @param string $target     See self::TARGET_* for details.
     * @param string $encoding   The character encoding to use for HTML.
     *
     * @return mixed   escaped character sequence.
     *
     * @throws Exception   if $variable cannot be escaped
     *                     for the given target.
     */
    public static function escape($variable, $target, $encoding = 'UTF-8') {

        if (is_resource($variable)) {
            self::typeError($variable);
        }

        if (is_object($variable)) {
            $variable = (string)$variable;
        }

        switch ($target) {
            case self::TARGET_HTML:
                if (is_array($variable)) {
                    self::typeError($variable, $target);
                }
                return htmlspecialchars($variable, ENT_XHTML, 'UTF-8');

            case self::TARGET_JS:
                return json_encode($variable);

            case self::TARGET_URL:
                if (is_array($variable)) {
                    self::typeError($variable, $target);
                }
                return urlencode($variable);

            default:
                throw new Exception('Invalid escape target ' . $target);
        }
    }
}

