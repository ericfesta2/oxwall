<?php

final class INSTALL_FeedBack
{
    private array $session = [
        'message' => [],
        'flag' => []
    ];

    public function __construct()
    {
        $installFeedback = OW::getSession()->get('OW-INSTALL-FEEDBACK');

        if ( $installFeedback !== null ) {
            $this->session = $installFeedback;
        }
    }

    public function __destruct()
    {
        OW::getSession()->set('OW-INSTALL-FEEDBACK', $this->session);
    }

    public function errorMessage( string $msg )
    {
        $this->session['message'][] = [
            'type' => 'error',
            'message' => $msg
        ];
    }

    public function errorFlag( string $flag )
    {
        $this->session['flag'][$flag] = true;
    }

    public function getFlag( string $flag ): bool
    {
        $out = !empty($this->session['flag'][$flag]);
        unset($this->session['flag'][$flag]);

        return $out;
    }

    public function getMessages(): array
    {
        $msgs = $this->session['message'];
        $this->session['message'] = [];

        return $msgs;
    }
}