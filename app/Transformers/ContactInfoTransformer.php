<?php

namespace App\Transformers;

use App\ContactInfo;
use League\Fractal\TransformerAbstract;

class ContactInfoTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(ContactInfo $contact)
    {
        return [
            'id'    => (int) $contact->hash_id,
            'value' => $contact->value,
        ];
    }

}
