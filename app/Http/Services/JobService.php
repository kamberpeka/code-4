<?php

namespace DTApi\Http\Services;

use DTApi\Http\Resources\JobResource;
use DTApi\Repository\Contracts\JobRepositoryContract;

class JobService
{
    /**
     * @var JobRepositoryContract
     */
    private static $jobRepository;

    /**
     * @param JobRepositoryContract $jobRepository
     */
    public function __construct(JobRepositoryContract $jobRepository)
    {
        self::$jobRepository = $jobRepository;
    }

    /**
     * @param DTApi\Http\Models\User $user
     */
    public static function userJobs(User $user){
        $usertype = '';
        $emergencyJobs = array();
        $noramlJobs = array();
        if ($user->is('customer')) {
            $jobs = self::$jobRepository->getCustomerJobs($user->id);
            $usertype = 'customer';
        } elseif ($user->is('translator')) {
            $jobs = self::$jobRepository->getTranslatorJobs($user->id);
            $usertype = 'translator';
        }
        if ($jobs) {
            // ToDo: Refactoring
            foreach ($jobs as $jobitem) {
                if ($jobitem->immediate == 'yes') {
                    $emergencyJobs[] = $jobitem;
                } else {
                    $noramlJobs[] = $jobitem;
                }
            }
            $noramlJobs = collect($noramlJobs)->each(function ($item, $key) use ($user) {
                $item['usercheck'] = Job::checkParticularJob($user->id, $item);
            })->sortBy('due')->all();
        }

        return [
            'emergencyJobs' => JobResource::collection($emergencyJobs),
            'noramlJobs' => JobResource::collection($noramlJobs),
            'cuser' => UserResource::collection($user),
            'usertype' => $usertype
        ];
    }

    /**
     * @param int $user_id
     * @param array $data
     * @return ServiceResponse
     */
    public static function store(int $user_id, array $data)
    {
        try {
            DB::beginTransaction();

            $job = self::$jobRepository->create(
                Arr::only($data, [
                    'customer_phone_type',
                    'customer_physical_type',
                    'due',
                    'immediate',
                    // ToDO: fill all possible fillable fields
                ]) + [
                    'user_id' => $user_id
                ]
            );

            DB::commit();

            return new ServiceResponse(true, $job);
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('JobService::store Exception Error: ' . $e->getMessage());

            return new ServiceResponse(false);
        }
    }
}