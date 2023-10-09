<?php

class Document {
    private int $id;
    private string $title;
    private string $delta;
    private int $user_id;
    private string $last_saved;


    public function getId(): int {
        return $this->id;
    }
    public function setId(int $id): void {
        $this->id = $id;
    }
    public function getTitle(): string {
        return $this->title;
    }
    public function setTitle(string $title): void {
        $this->title = $title;
    }
    public function getDelta(): string {
        return $this->delta;
    }
    public function setDelta(string $delta) {
        $this->delta = $delta;
    }
    public function getUserId(): int {
        return $this->user_id;
    }
    public function setUserId(int $user_id) {
        $this->user_id = $user_id;
    }
	public function getLast_Saved(): string {
		return $this->last_saved;
	}
	public function setLast_Saved(string $last_saved): void {
		$this->last_saved = $last_saved;
	}
}