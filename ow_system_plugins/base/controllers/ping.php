<?php

class BASE_CTRL_Ping extends OW_ActionController
{
    const string PING_EVENT = 'base.ping';

    public function index()
    {
        $request = json_decode($_POST['request'], true);
        $stack = $request['stack'];

        $responseStack = [];

        foreach ( $stack as $c )
        {
            $command = strip_tags(trim($c['command']));
            $params  = $c['params'];

            $event = new OW_Event(self::PING_EVENT . '.' . $command, $params);
            OW::getEventManager()->trigger($event);

            $event = new OW_Event(self::PING_EVENT, $c, $event->getData());
            OW::getEventManager()->trigger($event);

            $responseStack[] = [
                'command' => $command,
                'result' => $event->getData()
            ];
        }

        echo json_encode([
            'stack' => $responseStack
        ]);

        exit;
    }
}