<?php

namespace CodeProject\Http\Controllers;


use CodeProject\Repositories\ProjectRepository;
use CodeProject\Services\ProjectService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;

class ProjectController extends Controller
{
    /**
     * @var ProjectRepository
     */
    private $repository;

    /**
     * @var ProjectService
     */
    private $service;

    /**
     * ProjectController constructor.
     *
     * @param ProjectRepository $repository
     * @param ProjectService $service
     */
    public function __construct(ProjectRepository $repository, ProjectService $service)
    {
        $this->repository = $repository;

        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return $this->repository->all();
        //return $this->repository->with(['owner', 'client'])->all();
        return $this->repository->with(['owner', 'client'])->findWhere(['owner_id' => Authorizer::getResourceOwnerId()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->service->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //return $this->repository->find($id);
        //return $this->repository->with(['owner', 'client'])->find($id);

        //return['userId' => Authorizer::getResourceOwnerId()];

//        $userId = Authorizer::getResourceOwnerId();
//
//        if ($this->repository->isOwner($id, $userId) == false) {
//            return ['success' => false];
//        }

        try {
            //if ($this->checkProjectOwner($id) == false) {
            if ($this->checkProjectPermissions($id) == false) {
                return ['error' => 'Access forbidden'];
            }
            return $this->repository->with(['owner', 'client'])->find($id);
        } catch (ModelNotFoundException $e) {
            return [
                'error' => true,
                'message' => 'Não foi possível exibir o projeto! Projeto não encontrado!'
            ];
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //return $this->service->update($request->all(), $id);

        try {
            if ($this->checkProjectOwner($id) == false) {
                return ['error' => 'Access forbidden'];
            }
            return $this->service->update($request->all(), $id);
        } catch (ModelNotFoundException $e) {
            return [
                'error' => true,
                'message' => 'Não foi possível atualizar o projeto! Projeto não encontrado!'
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //return $this->repository->delete($id);

        try {
            if ($this->checkProjectOwner($id) == false) {
                return ['error' => 'Access forbidden'];
            }
            if ($this->repository->delete($id)) {
                return [
                    'success' => true,
                    'message' => 'Projeto apagado com sucesso'
                ];
            }
        } catch (ModelNotFoundException $e) {
            return [
                'error' => true,
                'message' => 'Não foi possível apagar o projeto! Projeto não encontrado!'
            ];
        }
    }

    public function addMember($id, $memberId)
    {
        return $this->service->addMember($id, $memberId);
    }

    public function removeMember($id, $memberId)
    {
        return $this->service->removeMember($id, $memberId);
    }

    public function isMember($id, $memberId)
    {
        return $this->service->isMember($id, $memberId);
    }

    private function checkProjectOwner($projectId)
    {
        $userId = Authorizer::getResourceOwnerId();

        return $this->repository->isOwner($projectId, $userId);
    }

    private function checkProjectMember($projectId)
    {
        $userId = Authorizer::getResourceOwnerId();

        return $this->repository->hasMember($projectId, $userId);
    }

    private function checkProjectPermissions($projectId)
    {
        if ($this->checkProjectOwner($projectId) or $this->checkProjectMember($projectId))
        {
            return true;
        }

        return false;

    }
}
