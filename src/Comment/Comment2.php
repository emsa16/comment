<?php

namespace Emsa\Comment;

use \LRC\Common\BaseModel;
use \LRC\Common\ValidationTrait;
use \LRC\Common\ValidationInterface;

/**
 * Comment class.
 *
 * @SuppressWarnings(PHPMD.ExitExpression)
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class Comment2 extends BaseModel implements ValidationInterface
{
    use ValidationTrait;

    // public $id;
    public $post_id;
    public $parent_id;
    public $user;
    // public $created;
    // public $edited;
    public $content;
    // public $upvote;
    // public $downvote;
    // public $deleted;



    public function __construct()
    {
        // $this->setNullables(['edited']);
        $this->setValidation([
            'content' => [
                [
                    'rule' => 'required',
                    'message' => 'Kommentaren kan inte vara tom.'
                ],
            ]
        ]);
    }



    public function timeElapsedString($datetime, $full = false)
    {
        $now = new \DateTime;
        $ago = new \DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7; //??????

        $string = array(
            'y' => ['år', 'år'],
            'm' => ['månad', 'månader'],
            'w' => ['vecka', 'veckor'],
            'd' => ['dag', 'dagar'],
            'h' => ['timme', 'timmar'],
            'i' => ['minut', 'minuter'],
            's' => ['sekund', 'sekunder'],
        );

        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $singPlur = $diff->$k > 1 ? 1 : 0;
                $v = $diff->$k . ' ' . $v[$singPlur];
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) {
            $string = array_slice($string, 0, 1);
        }

        return $string ? implode(', ', $string) . ' sedan' : 'nyligen';
    }
}
