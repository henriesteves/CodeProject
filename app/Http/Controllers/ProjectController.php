<?php

namespace CodeProject\Http\Controllers;


use CodeProject\Repositories\ProjectRepository;
use CodeProject\Services\ProjectService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

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
        return $this->repository->with(['owner', 'client'])->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->service->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //return $this->repository->find($id);
        //return $this->repository->with(['owner', 'client'])->find($id);

        try {
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //return $this->service->update($request->all(), $id);

        try {
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //return $this->repository->delete($id);

        try {
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
}
