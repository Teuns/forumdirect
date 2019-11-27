<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Warning Entity
 *
 * @property int $id
 * @property int|null $to_user_id
 * @property int|null $from_user_id
 * @property int|null $user_id
 * @property string|null $percentage
 * @property \Cake\I18n\FrozenTime|null $valid_until
 *
 * @property \App\Model\Entity\ToUser $to_user
 * @property \App\Model\Entity\FromUser $from_user
 * @property \App\Model\Entity\User $user
 */
class Warning extends Entity
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
        'percentage' => true,
        'valid_until' => true,
        'reason' => true
    ];
}
