<?php

namespace CodeProject\Http\Controllers;


use CodeProject\Repositories\ProjectNoteRepository;
use CodeProject\Services\ProjectNoteService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ProjectNoteController extends Controller
{
    /**
     * @var ProjectNoteRepository
     */
    private $repository;

    /**
     * @var ProjectNoteService
     */
    private $service;

    /**
     * ProjectNoteController constructor.
     *
     * @param ProjectNoteRepository $repository
     * @param ProjectNoteService $service
     */
    public function __construct(ProjectNoteRepository $repository, ProjectNoteService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //return $this->repository->all();
        return $this->repository->findWhere(['project_id' => $id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $this->service->create($request->all());

        try {
            return $this->service->create($request->all());
        } catch (QueryException $e) {
            return [
                'error' => true,
                'message' => 'Não foi possível criar a nota. Projeto não existe!'
            ];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $noteId)
    {
        //return $this->repository->find($id);
        return $this->repository->findWhere(['project_id' => $id, 'id' => $noteId]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $noteId)
    {
        //return $this->service->update($request->all(), $noteId);

        // TODO: impedir que o project_id da nota possa ser alterado

        try {
            if($this->service->update($request->all(), $noteId)) {
                return [
                    'success' => true,
                    'message' => 'Nota atualizada com sucesso!'
                ];
            }
        } catch (ModelNotFoundException $e) {
            return [
                'error' => true,
                'message' => 'Não foi possível atualizar a nota! Projeto ou nota não encontrado!'
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $noteId)
    {
        try {
            if($this->repository->delete($noteId)) {
                return [
                    'success' => true,
                    'message' => 'Nota apagado com sucesso!'
                ];
            }
        } catch (ModelNotFoundException $e) {
            return [
                'error' => true,
                'message' => 'Não foi possível apagar a nota! Projeto ou nota não encontrado!'
            ];
        }
    }
}
