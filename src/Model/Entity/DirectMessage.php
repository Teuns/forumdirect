<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DirectMessage Entity
 *
 * @property int $id
 * @property string $title
 * @property string $body
 * @property string|null $from_user_id
 * @property string|null $to_user_id
 * @property string $user_id
 *
 * @property \App\Model\Entity\FromUser $from_user
 * @property \App\Model\Entity\ToUser $to_user
 * @property \App\Model\Entity\User $user
 */
class DirectMessage extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'title' => true,
        'body' => true,
        'from_user_id' => true,
        'to_user_id' => true,
        'from_user' => true,
        'to_user' => true
    ];
}
