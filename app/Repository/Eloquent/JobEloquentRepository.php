<?php


namespace DTApi\Repository\Eloquent;


use DTApi\Repository\Contracts\JobRepositoryContract;

class JobEloquentRepository extends BaseEloquentRepository implements JobRepositoryContract
{
    public function model()
    {
        return Job::class;
    }

    /**
     * @param $user_id
     * @return Model
     */
    public function getCustomerJobs(int $user_id)
    {
        return $this->model->with(
            'user.userMeta',
            'user.average',
            'translatorJobRel.user.average',
            'language',
            'feedback'
        )->whereIn('status', ['pending', 'assigned', 'started'])
            ->where('user_id', $user_id)
            ->orderBy('due', 'asc')
            ->get();
    }

    /**
     * @param $user_id
     * @return Model
     */
    public function getTranslatorJobs(int $user_id){
        return $this->model->getTranslatorJobs($user_id, 'new')
            ->pluck('jobs')
            ->all();
    }
}