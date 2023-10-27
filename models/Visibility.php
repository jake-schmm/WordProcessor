<?php
enum Visibility: int {
    case PUBLIC = 1;
    case FRIENDS = 2;
    case MYSELF = 3;

    public static function getAllValues(): array {
        return [
            self::PUBLIC,
            self::FRIENDS,
            self::MYSELF,
        ];
    }
}