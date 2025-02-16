<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Proto;

/**
 */
class AddServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \Proto\EventRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function GetEvent(\Proto\EventRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/proto.AddService/GetEvent',
        $argument,
        ['\Proto\EventResponse', 'decode'],
        $metadata, $options);
    }

}
