<?php

namespace DSI\Repository;

use DSI;
use DSI\Entity\ProjectPost;
use DSI\Service\SQL;

class ProjectPostRepository
{
    public function insert(ProjectPost $projectPost)
    {
        $insert = array();
        $insert[] = "`projectID` = '" . (int)($projectPost->getProject()->getId()) . "'";
        $insert[] = "`userID` = '" . (int)($projectPost->getUser()->getId()) . "'";
        $insert[] = "`time` = NOW()";
        $insert[] = "`title` = '" . addslashes($projectPost->getTitle()) . "'";
        $insert[] = "`text` = '" . addslashes($projectPost->getText()) . "'";
        $insert[] = "`commentsCount` = '" . (int)($projectPost->getCommentsCount()) . "'";

        $query = new SQL("INSERT INTO `project-posts` SET " . implode(', ', $insert) . "");
        $query->query();

        $projectPost->setId($query->insert_id());
    }

    public function save(ProjectPost $projectPost)
    {
        $query = new SQL("SELECT id FROM `project-posts` WHERE id = '{$projectPost->getId()}' LIMIT 1");
        $existingPost = $query->fetch();
        if (!$existingPost)
            throw new DSI\NotFound('postID: ' . $projectPost->getId());

        $insert = array();
        $insert[] = "`projectID` = '" . (int)($projectPost->getProject()->getId()) . "'";
        $insert[] = "`userID` = '" . (int)($projectPost->getUser()->getId()) . "'";
        $insert[] = "`title` = '" . addslashes($projectPost->getTitle()) . "'";
        $insert[] = "`text` = '" . addslashes($projectPost->getText()) . "'";
        $insert[] = "`commentsCount` = '" . (int)($projectPost->getCommentsCount()) . "'";

        $query = new SQL("UPDATE `project-posts` SET " . implode(', ', $insert) . " WHERE `id` = '{$projectPost->getId()}'");
        $query->query();
    }

    public function remove(ProjectPost $projectPost)
    {
        $query = new SQL("SELECT id FROM `project-posts` WHERE id = '{$projectPost->getId()}' LIMIT 1");
        $existingPost = $query->fetch();
        if (!$existingPost)
            throw new DSI\NotFound('postID: ' . $projectPost->getId());

        $query = new SQL("DELETE FROM `project-posts` WHERE id = '{$projectPost->getId()}'");
        $query->query();
    }

    public function getById(int $id): ProjectPost
    {
        return $this->getPostWhere([
            "`id` = {$id}"
        ]);
    }

    /**
     * @param $dbPost
     * @return ProjectPost
     */
    private function buildPostFromData($dbPost)
    {
        $post = new ProjectPost();
        $post->setId($dbPost['id']);

        $post->setProject(
            (new ProjectRepositoryInAPC())->getById($dbPost['projectID'])
        );
        $post->setUser(
            (new UserRepository())->getById($dbPost['userID'])
        );
        $post->setTime($dbPost['time']);
        $post->setTitle($dbPost['title']);
        $post->setText($dbPost['text']);
        $post->setCommentsCount($dbPost['commentsCount']);

        return $post;
    }

    public function getAll()
    {
        return $this->getPostsWhere([
            "1"
        ]);
    }

    public function getByUser(DSI\Entity\User $user)
    {
        return $this->getPostsWhere([
            "`userID` = '" . $user->getId() . "'"
        ]);
    }

    public function getByProjectID(int $projectID)
    {
        return $this->getPostsWhere([
            "`projectID` = '{$projectID}'"
        ]);
    }

    /**
     * @param int[] $projectIDs
     * @return DSI\Entity\ProjectPost[]
     */
    public function getByProjectIDs($projectIDs)
    {
        if (count($projectIDs) == 0)
            return [];

        return $this->getPostsWhere([
            "`projectID` IN (" . implode(', ', $projectIDs) . ")"
        ]);
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `project-posts`");
        $query->query();
    }

    /**
     * @param $where
     * @return ProjectPost
     * @throws DSI\NotFound
     */
    private function getPostWhere($where)
    {
        $dbPost = $this->getPostsWhere($where);
        if (count($dbPost) < 1)
            throw new DSI\NotFound();

        return $dbPost[0];
    }

    /**
     * @param array $where
     * @return ProjectPost[]
     */
    private function getPostsWhere($where)
    {
        $posts = [];
        $query = new SQL("SELECT 
            id, projectID, userID, `time`, title, text, commentsCount
            FROM `project-posts` WHERE " . implode(' AND ', $where) . "
            ORDER BY `id` DESC");
        foreach ($query->fetch_all() AS $dbPost) {
            $posts[] = $this->buildPostFromData($dbPost);
        }
        return $posts;
    }
}