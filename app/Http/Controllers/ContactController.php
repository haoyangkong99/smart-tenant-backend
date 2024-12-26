<?php
    namespace App\Http\Controllers;

    use App\Models\Contact;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Validator;

    class ContactController extends Controller
    {
        /**
         * Display a listing of contacts.
         */

         public function index()
         {
             $contacts = Contact::all();

             if ($contacts->isEmpty()) {
               return $this->getQueryAllNotFoundResponse();
             }

             return $this->successfulQueryResponse($contacts);
         }
        /**
         * Store a newly created contact in storage.
         */
        public function store(Request $request)
        {

            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|string',
                'url' => 'nullable|string',
                'phone' => 'required|string',
                'address' => 'required|string',
                'remark' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors());

            }

            $contact = Contact::create([
                'name' => $request->name,
                'email' => $request->email,
                'url' => $request->url,
                'phone' => $request->phone,
                'address' => $request->address,
                'remark' => $request->remark,
                'created_by' => $this->getCurrentUserId(),
            ]);

            return $this->successfulCreationResponse($contact) ;
        }

        /**
         * Display the specified contact.
         */
        public function show($id)
        {
            try {
                $contact = Contact::findOrFail($id);
                return $this->successfulQueryResponse($contact);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return $this->getQueryIDNotFoundResponse('Contact',$id);
            }

        }

        /**
         * Update the specified contact in storage.
         */
        public function update(Request $request, $id)
        {
            $contact = Contact::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|string|email',
                'url' => 'nullable|string',
                'phone' => 'required|string',
                'address' => 'required|string',
                'remark' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors());
            }

            $contact->update([
                'name' => $request->name,
                'email' => $request->email,
                'url' => $request->url,
                'phone' => $request->phone,
                'address' => $request->address,
                'remark' => $request->remark,
                'modified_by' => $this->getCurrentUserId(),
            ]);

            return $this->successfulUpdateResponse($contact);
        }

        /**
         * Remove the specified contact from storage.
         */
        public function destroy($id)
        {
            try {
                $contact = Contact::findOrFail($id);
                $contact->delete();

                return $this->successfulDeleteResponse($id);
            }
            catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
            {
                return $this->getDeleteFailureResponse();
            };

        }

        /**
         * Get the current user ID.
         */

    }
?>