<?php

namespace App\Repositories;

use App\Models\ExternalServiceConfiguration;



class ExternalServiceConfigurationRepository
{
    /**
     * $externalServiceConfiguration variable
     *
     * @var object
     */
    protected $externalServiceConfiguration;

    /**
     * __construct function
     *
     * @param \App\Models\ExternalServiceConfiguration $externalServiceConfiguration
     */
    public function __construct( ExternalServiceConfiguration $externalServiceConfiguration)
    {
        $this->externalServiceConfiguration = $externalServiceConfiguration;
    }

    /**
     * getOne function
     *
     * @param array $conditions
     * @return mixed
     */
    public function getOne($conditions)
    {
        return $this->externalServiceConfiguration->where($conditions)->first();
    }

    /**
     * update function
     *
     * @param array $conditions
     * @param array $data
     * @return object
     */
    //    public function update($conditions, $data)
//    {
//        $modelData = $this->billerConfiguration->where($conditions)->first();
//        $modelData->update($data);
//
//        return $modelData;
//    }
}
