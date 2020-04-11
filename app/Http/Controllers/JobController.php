<?php


namespace DTApi\Http\Controllers;

use DTApi\Http\Resources\JobResource;
use DTApi\Http\Services\JobService;
use DTApi\Repository\Contracts\JobRepositoryContract;
use http\Env\Response;

/**
 * Class JobController
 * @package DTApi\Http\Controllers
 */
class JobController extends Controller
{
    /**
     * @var JobRepositoryContract
     */
    protected $jobRepository;

    /**
     * BookingController constructor.
     * @param JobRepositoryContract $jobRepository
     */
    public function __construct(JobRepositoryContract $jobRepository)
    {
        $this->jobRepository = $jobRepository;
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return JobResource
     */
    public function show(Request $request, int $id)
    {
        return new JobResource(
            $this->jobRepository->findOrFail($id)
        );
    }

    /**
     * @param DTApi\Http\Models\User
     * @return array
     */
    public function getUsersJobs(User $user)
    {
        $response = JobService::userJobs($user);

        return response()->json($response);
    }

    /**
     * @param User $user
     * @param Request $request
     * @return mixed
     */
    public function store(User $user, Request $request)
    {
        $response = JobService::store($user->id, $request->all());

        if($response->success()){
            return new JobResource($response->getModel());
        } else {
            return response()->json(null, 400);
        }
    }
}