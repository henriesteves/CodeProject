<?php

namespace CodeProject\Services;


use CodeProject\Repositories\ProjectMemberRepository;
use CodeProject\Validators\ProjectMemberValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Prettus\Validator\Exceptions\ValidatorException;

class ProjectMemberService
{
    /**
     * @var ProjectMemberRepository
     */
    protected $repository;

    /**
     * @var ProjectMemberValidator
     */
    protected $validator;

    public function __construct(ProjectMemberRepository $repository, ProjectMemberValidator $validator)
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
                'message' => 'Não foi possível criar o membro. Projeto não existe!'
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
                'message' => 'Não foi possível atualizar o membro! Projeto ou membro não encontrado!'
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
                'message' => 'Não foi possível apagar o membro! Projeto ou membro não encontrado!'
            ];
        }
    }
}