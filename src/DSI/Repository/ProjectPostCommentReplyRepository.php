<?php

namespace DSI\Repository;

use DSI\Entity\ProjectPostCommentReply;
use DSI\Entity\User;
use DSI\NotFound;
use DSI\Service\SQL;

class ProjectPostCommentReplyRepository
{
    public function insert(ProjectPostCommentReply $reply)
    {
        if (!$reply->getProjectPostComment())
            throw new \InvalidArgumentException();
        if (!$reply->getUserId() OR !$reply->getUserId())
            throw new \InvalidArgumentException();
        if ($reply->getComment() == '')
            throw new \InvalidArgumentException();

        $insert = array();
        $insert[] = "`commentID` = '" . (int)($reply->getProjectPostComment()->getId()) . "'";
        $insert[] = "`userID` = '" . (int)($reply->getUserId()) . "'";
        $insert[] = "`comment` = '" . addslashes($reply->getComment()) . "'";
        $insert[] = "`time` = NOW()";

        $query = new SQL("INSERT INTO `project-post-comment-replies` SET " . implode(', ', $insert) . "");
        $query->query();

        $reply->setId($query->insert_id());
    }

    public function save(ProjectPostCommentReply $reply)
    {
        if (!$reply->getProjectPostComment())
            throw new \InvalidArgumentException();
        if (!$reply->getUserId())
            throw new \InvalidArgumentException();
        if ($reply->getComment() == '')
            throw new \InvalidArgumentException();

        $query = new SQL("SELECT id FROM `project-post-comment-replies` WHERE id = '{$reply->getId()}' LIMIT 1");
        $existingPost = $query->fetch();
        if (!$existingPost)
            throw new NotFound('commentID: ' . $reply->getId());

        $insert = array();
        $insert[] = "`commentID` = '" . (int)($reply->getProjectPostComment()->getId()) . "'";
        $insert[] = "`userID` = '" . (int)($reply->getUserId()) . "'";
        $insert[] = "`comment` = '" . addslashes($reply->getComment()) . "'";

        $query = new SQL("UPDATE `project-post-comment-replies` SET " . implode(', ', $insert) . " WHERE `id` = '{$reply->getId()}'");
        $query->query();
    }

    public function remove(ProjectPostCommentReply $reply)
    {
        $query = new SQL("SELECT id FROM `project-post-comment-replies` WHERE id = '{$reply->getId()}' LIMIT 1");
        $existingPost = $query->fetch();
        if (!$existingPost)
            throw new NotFound('commentID: ' . $reply->getId());

        $query = new SQL("DELETE FROM `project-post-comment-replies` WHERE `id` = '{$reply->getId()}'");
        $query->query();
    }

    public function getById(int $id): ProjectPostCommentReply
    {
        return $this->getObjectWhere([
            "`id` = {$id}"
        ]);
    }

    private function buildPostFromData($dbReply)
    {
        $reply = new ProjectPostCommentReply();
        $reply->setId($dbReply['id']);

        $reply->setProjectPostComment(
            (new ProjectPostCommentRepository())->getById($dbReply['commentID'])
        );
        $reply->setUser(
            (new UserRepository())->getById($dbReply['userID'])
        );
        $reply->setComment($dbReply['comment']);
        $reply->setTime($dbReply['time']);

        return $reply;
    }

    public function getAll()
    {
        return $this->getObjectsWhere([
            "1"
        ]);
    }

    public function getByUser(User $user)
    {
        return $this->getObjectsWhere([
            "`userID` = '" . $user->getId() . "'"
        ]);
    }

    public function getByCommentID(int $commentID)
    {
        return $this->getObjectsWhere([
            "`commentID` = '{$commentID}'"
        ]);
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `project-post-comment-replies`");
        $query->query();
    }

    private function getObjectWhere($where)
    {
        $dbPost = $this->getObjectsWhere($where);
        if (count($dbPost) < 1)
            throw new NotFound();

        return $dbPost[0];
    }

    private function getObjectsWhere($where)
    {
        $replies = [];
        $query = new SQL("SELECT 
            id, commentID, userID, comment, `time`
            FROM `project-post-comment-replies` WHERE " . implode(' AND ', $where) . "
            ORDER BY `id` ASC");
        foreach ($query->fetch_all() AS $dbPost) {
            $replies[] = $this->buildPostFromData($dbPost);
        }
        return $replies;
    }
}