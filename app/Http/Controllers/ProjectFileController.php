<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectRepository;
use CodeProject\Services\ProjectService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProjectFileController extends Controller
{
    /**
     * @var ProjecFileRepository
     */
    private $repository;

    /**
     * @var ProjectFileService
     */
    private $service;

    /**
     * ProjectFileController constructor.
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $file = $request->file('file');

            $extension = $file->getClientOriginalExtension();

            //Storage::put($request->name . '.' . $extension, File::get($file));

            $data['project_id'] = $request->project_id;
            $data['file'] = $file;
            $data['extension'] = $extension;
            $data['name'] = $request->name;
            $data['description'] = $request->description;

            $this->service->createFile($data);
        } catch (ModelNotFoundException $e) {
            return [
                'error' => true,
                'message' => 'Não foi possível fazer o upload do arquivo'
            ];
        }
    }

    public function destroy($projectId, $fileId)
    {
        try {
            return $this->service->deleteFile($projectId, $fileId);
        } catch (ModelNotFoundException $e) {
            return [
                'error' => true,
                'message' => 'Não foi possível apagar o arquivo'
            ];
        }
    }
}
