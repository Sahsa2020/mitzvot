<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\Invoice
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $buyer_id
 * @property int $user_id
 * @property float $amount
 * @property int $count
 * @property int $type
 * @property int $status
 * @property string $invoice_key
 * @property int $del_flg
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereBuyerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereDelFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereInvoiceKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invoice whereUserId($value)
 */
class Invoice extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'cbox_invoices';

}
