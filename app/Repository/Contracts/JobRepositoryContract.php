<?php


namespace DTApi\Repository\Contracts;


interface JobRepositoryContract
{
    /**
     * @param int $user_id
     * @return Model
     */
    public function getCustomerJobs(int $user_id);

    /**
     * @param $user_id
     * @return Model
     */
    public function getTranslatorJobs(int $user_id);
}