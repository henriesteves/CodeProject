<?php

namespace CodeProject\Services;


use CodeProject\Repositories\ProjectTaskRepository;
use CodeProject\Validators\ProjectTaskValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Prettus\Validator\Exceptions\ValidatorException;

class ProjectTaskService
{
    /**
     * @var ProjectTaskRepository
     */
    protected $repository;

    /**
     * @var ProjectTaskValidator
     */
    protected $validator;

    public function __construct(ProjectTaskRepository $repository, ProjectTaskValidator $validator)
    {
        $this->repository = $repository;

        $this->validator = $validator;
    }

    public function create(array $data)
    {
        try {

            $this->validator->with($data)->passesOrFail();

            return $this->repository->create($data);

        } catch (ValidatorException $e) {

            return [
                'error' => true,
                'message' => $e->getMessageBag()
            ];
        } catch (QueryException $e) {
            return [
                'error' => true,
                'message' => 'Não foi possível criar a tarefa. Projeto não existe!'
            ];
        }
    }

    public function update(array $data, $id)
    {
        try {

            $this->validator->with($data)->passesOrFail();

            //return $this->repository->update($data, $id);

            if ($this->repository->update($data, $id)) {
                return [
                    'success' => true,
                    'message' => 'Tarefa atualizada com sucesso!'
                ];
            }

        } catch (ValidatorException $e) {
            return [
                'error' => true,
                'message' => $e->getMessageBag()
            ];
        } catch (ModelNotFoundException $e) {
            return [
                'error' => true,
                'message' => 'Não foi possível atualizar a tarefa! Projeto ou tarefa não encontrado!'
            ];
        }
    }

    public function delete($id)
    {
        try {
            if ($this->repository->delete($id)) {
                return [
                    'success' => true,
                    'message' => 'Tarefa apagado com sucesso!'
                ];
            }
        } catch (ModelNotFoundException $e) {
            return [
                'error' => true,
                'message' => 'Não foi possível apagar a tarefa! Projeto ou tarefa não encontrado!'
            ];
        }
    }
}