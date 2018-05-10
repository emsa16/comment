<?php

namespace Emsa\Comment;

use \LRC\Form\BaseModel;
use \LRC\Repository\SoftManagedModelInterface;
use \LRC\Repository\SoftManagedModelTrait;
use \Emsa\User\User;

/**
 * Comment class.
 *
 * @SuppressWarnings(PHPMD.ExitExpression)
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class Comment extends BaseModel implements SoftManagedModelInterface
{
    use SoftManagedModelTrait;

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
        $this->setReferences([
            'userObject' => [
                'attribute' => 'user',
                'model' => User::class,
                'magic' => true
            ]
        ]);

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



    public function timeElapsedString($datetime)
    {
        $now = new \DateTime;
        $ago = new \DateTime($datetime);
        $diff = $now->diff($ago);
        $diff->w = floor($diff->d / 7);

        $timeValues = array(
            'y' => ['år', 'år'],
            'm' => ['månad', 'månader'],
            'w' => ['vecka', 'veckor'],
            'd' => ['dag', 'dagar'],
            'h' => ['timme', 'timmar'],
            'i' => ['minut', 'minuter'],
            's' => ['sekund', 'sekunder'],
        );

        foreach ($timeValues as $k => &$v) {
            if ($diff->$k != 0) {
                $singPlur = $diff->$k > 1 ? 1 : 0;
                $string = $diff->$k . ' ' . $v[$singPlur] . ' sedan';
                break;
            }
        }

        return isset($string) ? $string : 'nyligen';
    }
}
