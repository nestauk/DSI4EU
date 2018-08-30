<?php

namespace Controllers\Upload;

use Actions\OpenResources\OpenResourceCreate;
use DSI\Entity\User;
use DSI\NotFound;
use DSI\Repository\UserRepo;
use DSI\Service\Auth;
use League\Csv\Reader;
use League\Csv\Statement;
use Models\Relationship\ClusterLang;
use Models\Resource;
use Models\TypeOfResource;
use Services\JsonResponse;
use Services\Request;
use Services\Response;
use Services\View;

class UploadResourcesController
{
    /** @var User */
    private $loggedInUser;

    /** @var boolean */
    private $save;

    public function handle()
    {
        $this->loggedInUser = (new Auth())->getUserIfLoggedIn();
        if (!($this->userCanUpload($this->loggedInUser)))
            return (new Response('Please login before accessing this page', Response::HTTP_FORBIDDEN))->send();

        if (Request::isGet())
            return $this->get();
        elseif (Request::isPost())
            return $this->upload();

        return true;
    }

    private function get()
    {
        return View::render(__DIR__ . '/../../Views/upload/upload-resources.php', [
            'loggedInUser' => $this->loggedInUser,
        ]);
    }

    /**
     * @param bool $save
     * @return UploadResourcesController
     */
    public function setSave(bool $save)
    {
        $this->save = $save;
        return $this;
    }

    public function upload()
    {
        $response = [];
        $clusters = [];
        $types = [];
        $author = null;
        $csv = Reader::createFromPath($_FILES['file']['tmp_name'], 'r');
        $csv->setHeaderOffset(0); //set the CSV header offset
        $stmt = (new Statement());
        $records = $stmt->process($csv);
        foreach ($records as $record) {
            $errors = [];
            if (trim($record[Resource::Title]) === '')
                $errors[] = 'Please add a title';
            if (trim($record[Resource::Description]) === '')
                $errors[] = 'Please add a description';
            if ($record[Resource::LinkText] AND !$record[Resource::LinkUrl])
                $errors[] = 'Please add a resource url';
            if (!$record[Resource::LinkText] AND $record[Resource::LinkUrl])
                $errors[] = 'Please add a resource text';
            if (trim($record[Resource::Author]) === '')
                $errors[] = 'Please set an author (email)';
            else {
                $author = $this->getAuthor($record[Resource::Author]);
                if (!$author)
                    $errors[] = 'Invalid author email: ' . $record[Resource::Author];
            }
            if ($record[Resource::Clusters]) {
                $clusterNames = explode(';', $record[Resource::Clusters]);
                $clusterNames = array_map('trim', $clusterNames);
                foreach ($clusterNames AS $clusterName) {
                    /** @var ClusterLang $cluster */
                    $cluster = ClusterLang::where(ClusterLang::Title, $clusterName)->first();
                    if (!$cluster)
                        $errors[] = 'Invalid cluster name: ' . $clusterName;
                    else
                        $clusters[$cluster->getClusterId()] = "1";
                }
            }
            if ($record[Resource::Types]) {
                $typeNames = explode(';', $record[Resource::Types]);
                $typeNames = array_map('trim', $typeNames);
                foreach ($typeNames AS $typeName) {
                    /** @var TypeOfResource $type */
                    $type = TypeOfResource::where(TypeOfResource::Name, $typeName)->first();
                    if (!$type)
                        $errors[] = 'Invalid type name: ' . $typeName;
                    else
                        $types[$type->getId()] = "1";
                }
            }

            if ($this->save AND !$errors) {
                $exec = new OpenResourceCreate();
                $exec->executor = $this->loggedInUser;
                $exec->title = $record[Resource::Title];
                $exec->description = $record[Resource::Description];
                $exec->linkText = $record[Resource::LinkText];
                $exec->linkUrl = $record[Resource::LinkUrl];
                $exec->clusters = $clusters;
                $exec->types = $types;
                $exec->authorID = $author->getId();
                $exec->exec();
            } else {
                $record['errors'] = $errors;
                $response[] = $record;
            }
        }

        return (new JsonResponse($response))->send();
    }

    /**
     * @param User $loggedInUser
     * @return bool
     */
    private function userCanUpload($loggedInUser): bool
    {
        return (bool)($loggedInUser AND ($loggedInUser->isCommunityAdmin() OR $loggedInUser->isEditorialAdmin()));
    }

    /**
     * @param $email
     * @return User | null
     */
    private function getAuthor($email)
    {
        $author = null;
        try {
            $author = (new UserRepo())->getByEmail($email);
        } catch (NotFound $e) {
        }

        return $author;
    }
}