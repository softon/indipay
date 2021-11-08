<?php namespace Softon\Indipay\Exceptions;

/**
 * Invalid Request Exception
 *
 * Thrown when a request is invalid or missing required fields.
 */
class IndipayParametersMissingException extends \Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function render($request)
    {
        return $this->getMessage();
    }
}