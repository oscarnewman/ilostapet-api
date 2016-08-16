<?php

namespace App\Transformers;

use App\Contact;
use League\Fractal\TransformerAbstract;

class ContactTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(Contact $contact)
    {
        return [
            'id'    => (int) $contact->hash_id,
            'value' => $contact->value,
        ];
    }

}
