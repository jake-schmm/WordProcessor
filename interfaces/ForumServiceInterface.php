<?php

interface ForumServiceInterface {
    public function createForumPost(int $documentId, int $visibility): bool;

}