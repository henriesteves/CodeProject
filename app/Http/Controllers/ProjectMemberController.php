<?php

namespace CodeProject\Http\Controllers;


use CodeProject\Repositories\ProjectMemberRepository;
use CodeProject\Services\ProjectMemberService;
use Illuminate\Http\Request;

class ProjectMemberController extends Controller
{
    /**
     * @var ProjectMemberRepository
     */
    private $repository;

    /**
     * @var ProjectMemberService
     */
    private $service;

    /**
     * ProjectController constructor.
     *
     * @param ProjectMemberRepository $repository
     * @param ProjectMemberService $service
     */
    public function __construct(ProjectMemberRepository $repository, ProjectMemberService $service)
    {
        $this->repository = $repository;

        $this->service = $service;
    }

    public function index($id)
    {
        //return $this->repository->all();
        return $this->repository->findWhere(['project_id' => $id]);
    }
}
