<?php

namespace CodeProject\Services;


use CodeProject\Repositories\ProjectRepository;
use CodeProject\Validators\ProjectValidator;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Filesystem\Filesystem;
use Prettus\Validator\Exceptions\ValidatorException;

//use Illuminate\Support\Facades\File;
//use Illuminate\Support\Facades\Storage;

class ProjectService
{
    /**
     * @var ProjectRepository
     */
    protected $repository;

    /**
     * @var ProjectValidator
     */
    protected $validator;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var Storage
     */
    protected $storage;

    public function __construct(ProjectRepository $repository, ProjectValidator $validator, Filesystem $filesystem, Storage $storage)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->filesystem = $filesystem;
        $this->storage = $storage;
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
        }
    }

    public function update(array $data, $id)
    {
        try {

            $this->validator->with($data)->passesOrFail();

            return $this->repository->update($data, $id);

        } catch (ValidatorException $e) {

            return [
                'error' => true,
                'message' => $e->getMessageBag()
            ];
        }
    }

    public function addMember($project_id, $member_id)
    {
        $project = $this->repository->find($project_id);

        if (!$this->isMember($project_id, $member_id)) {
            $project->members()->attach($member_id);
        }

        return $project->members()->get();
    }

    public function removeMember($project_id, $member_id)
    {
        $project = $this->repository->find($project_id);

        $project->members()->detach($member_id);

        return $project->members()->get();
    }

    public function isMember($project_id, $member_id)
    {
        $project = $this->repository->find($project_id)->members()->find(['member_id' => $member_id]);

        if (count($project)) {
            return true;
        }

        return false;
    }

    public function createFile(array $data)
    {
        $project = $this->repository->skipPresenter()->find($data['project_id']);
        $projectFile = $project->files()->create($data);

        //Storage::put($data['name'] . '.' . $data['extension'], File::get($data['file']));
        $this->storage->put($projectFile->id . '.' . $data['extension'], $this->filesystem->get($data['file']));
    }

    public function deleteFile($project_id, $file_id)
    {
        $project = $this->repository->skipPresenter()->find($project_id);
        if ($project) {
            $projectFile = $project->files->find($file_id);
            if ($projectFile) {
                $this->storage->delete($projectFile->id . '.' . $projectFile->extension);
                $projectFile->delete($file_id);
            } else {
                return [
                    'error' => true,
                    'message' => 'Arquivo não encontrado'
                ];
            }
        }
    }
}