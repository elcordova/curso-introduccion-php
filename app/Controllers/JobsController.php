<?php
namespace App\Controllers;

use App\Models\Job;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response\RedirectResponse;
use Respect\Validation\Validator as v;

class JobsController extends BaseController {
    public function indexAction() {
        // $jobs = Job::all(); //get only  with deleted_at null;
        
        $jobs = Job::withTrashed()->get(); // all trash inclusive deleted_at
        return $this->renderHTML('jobs/index.twig',compact('jobs'));
    }

    public function deleteAction(ServerRequest $request) {
        $params = $request->getQueryParams();
        $job = Job::findOrFail($params['id']);
        $job->delete();
        return New RedirectResponse('/jobs');
    }

    public function getAddJobAction($request) {
        $responseMessage = null;

        if ($request->getMethod() == 'POST') {
            $postData = $request->getParsedBody();
            $jobValidator = v::key('title', v::stringType()->notEmpty())
                  ->key('description', v::stringType()->notEmpty());

            try {
                $jobValidator->assert($postData);
                $postData = $request->getParsedBody();

                $files = $request->getUploadedFiles();
                $logo = $files['logo'];
                
                $filePath = "";
                if($logo->getError() == UPLOAD_ERR_OK) {
                    $fileName = $logo->getClientFilename();
                    $filePath = "uploads/$fileName";
                    $logo->moveTo($filePath);
                }
                $job = new Job();
                $job->title = $postData['title'];
                $job->description = $postData['description'];
                $job->image = $filePath;
                $job->save();

                $responseMessage = 'Saved';
            } catch (\Exception $e) {
                $responseMessage = $e->getMessage();
            }
        }

        return $this->renderHTML('addJob.twig', [
            'responseMessage' =>$responseMessage
        ]);
    }
}