<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Report Entity
 *
 * @property int $id
 * @property string|null $type
 * @property int|null $to_id
 * @property int|null $user_id
 * @property string|null $reason
 *
 * @property \App\Model\Entity\To $to
 * @property \App\Model\Entity\User $user
 */
class Report extends Entity
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
        'type' => true,
        'to_id' => true,
        'reason' => true
    ];
}
