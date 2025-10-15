<?php

/**
 * ---------------------------------------------------
 *  Desarrollado por: Jorge Méndez - Programandoweb
 *  Correo: lic.jorgemendez@gmail.com
 *  Celular: 3115000926
 *  website: Programandoweb.net
 *  Proyecto: Ivoolve *  
 * ---------------------------------------------------
 */

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use Spatie\Permission\Models\Role;
use App\Repositories\AuthRepository;
use App\Services\EmailService;
use App\Models\UserCharacteristics;
use App\Models\MasterTable;



class AuthController extends Controller
{
    protected $loginRepository;

    public function __construct(AuthRepository $loginRepository)
    {
        $this->loginRepository = $loginRepository;
    }

    public function init(Request $request){
        return response()->success([], 'Register active on', 200);     
    }

    public function shoppingAuth(Request $request)
    {
        try {
            $user = User::where("email", $request->email)->first();

            if ($user && 
                $user->phone_number == $request->phone_number &&
                $user->name == $request->name) {
                // El usuario ya existe y coincide la información
            } else {
                $user = User::updateOrCreate(
                    ['email' => $request->email],
                    [
                        'name'          => $request->name,
                        'phone_number'  => $request->phone_number,
                        'password'      => bcrypt($request->password),
                    ]
                );

                $role = Role::findByName('clients', 'api');
                $user->assignRole($role);
            }

            $credentials = $request->only(['email', 'password']);
            if (!$token = auth('api')->attempt($credentials)) {
                return response()->error('Credenciales incorrectas', 401);
            }

            $loginData              = $this->loginRepository->login($credentials);
            $user                   = $loginData['user'];
            $user['permissions']    = $loginData['permissions'];
            $is_admin               = $loginData['is_admin'];

            return response()->success(compact('user', 'token', 'is_admin'), 'Usuario autenticado exitosamente', 200);            

        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $user   =   $request->user();
            $user   =   User::find($user->id);
            $user->update($request->all());
            return response()->success(compact('user'), 'Usuario actualizado exitosamente', 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function profile(Request $request)
    {
        try {
            $user = $request->user();
            return response()->success(compact('user'), 'Perfil de usuario obtenido', 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }


    public function register(Request $request)
    {
        try {
            // Verificar si el correo ya está registrado
            $existingUser = User::where('email', $request->email)->first();

            if ($existingUser) {
                if (!empty($existingUser->confirmation_code)) {
                    $message  = 'Debes confirmar tu correo.';
                    $redirect = "AuthLoginScreen";
                    return response()->success(compact("message", "redirect", "existingUser"), 200);
                } else if (!is_null($existingUser->email_verified_at)) {
                    $message  = 'El correo ya está registrado. Puedes iniciar sesión o restablecer tu contraseña.';
                    $redirect = "AuthLoginScreen";
                    return response()->error(compact("message", "redirect"), 500);
                } else {
                    $message  = 'Este correo ya existe, puedes iniciar sesión o recuperar la contraseña.';
                    $redirect = "AuthLoginScreen";
                    return response()->success(compact("message", "redirect"), 200);
                }
            }

            // Código de confirmación (suspendido para evitar spam)
            $confirmationCode = null;

            // 🔹 Captura de origen de marketing
            $marketing          = $request->input('marketing', []);
            $marketingSource    = $marketing['detected_source'] ?? null;
            $firstTouch         = $marketing['first_touch'] ?? now();
            $marketingData      = !empty($marketing) ? json_encode($marketing) : null;

            // Crear usuario
            $user = User::create([
                'name'              => $request->name,
                'email'             => $request->email,
                'phone_number'      => $request->phone_number,
                'password'          => bcrypt($request->password),
                'confirmation_code' => $confirmationCode,
                'status'            => $request->has("role") && $request->role === 'providers' ? 'solicitud' : 'activo',

                // 🔹 Campos de marketing
                'marketing_source'  => $marketingSource,
                'first_touch_at'    => $firstTouch,
                'marketing_data'    => $marketingData,
            ]);

            // Asignación de roles
            $message  = [];
            $redirect = "AuthLoginScreen";

            if ($request->has("role")) {
                $role = Role::findByName($request->role, 'api');
                $user->assignRole($role);
                $redirect = "AuthRegisterBusinessSuccessScreen";

                $master_tables = MasterTable::where(['grupo' => 'email_register_business'])->first();
                if ($master_tables) {
                    $message["asunto"]  = $master_tables->label;
                    $message["message"] = $master_tables->description;
                }
            } else {
                $role = Role::findByName('clients', 'api');
                $user->assignRole($role);
            }

            // Eliminar código antes de devolver
            if (!empty($user->confirmation_code)) {
                unset($user->confirmation_code);
            }

            return response()->success(
                compact("user", "redirect", "message"),
                'Usuario registrado exitosamente. Revisa tu correo para confirmar el registro.',
                200
            );
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }


   
    public function registerOLD(Request $request)
    {
        try {
            
            // Verificar si el correo ya está registrado
            $existingUser   =   User::where('email', $request->email)->first();
            $error          =   false;
                       

            if ($existingUser) {
                //p($existingUser);
                // Verificar si el usuario tiene un código de confirmación pendiente
                if (!empty($existingUser->confirmation_code)) {
                    $user       =   $existingUser;
                    $message    =   'Debes confirmar tu correo.';
                    $redirect   =   "AuthLoginScreen";
                    return response()->success(compact("message","redirect","user"), 200);
                }else if (!is_null($existingUser->email_verified_at)) {
                    // Verificar si el correo ya está verificado
                    $message    =   'El correo ya está registrado. Puedes iniciar sesión o restablecer tu contraseña.';
                    $redirect   =   "AuthLoginScreen";
                    return response()->error(compact("message","redirect"), 500);                    
                }else{
                    $message    =   'Este correo ya existe, puedes iniciar sesión o recuperar la contraseña.';
                    $redirect   =   "AuthLoginScreen";
                    return response()->success(compact("message","redirect"), 200);
                }                

            }

            // Generar código de confirmación
            $confirmationCode   =   random_int(1000, 9999);
            /**
             * Vanos a sobreescribir  
             * $confirmationCode
             * esto porque no vamos a enviar por ahora emails para evitar spam
             */
            //$confirmationCode     =   1234;
            $confirmationCode       =   null;

            // Crear usuario
            $user = User::create([
                'name'                  => $request->name,
                'email'                 => $request->email,
                'phone_number'          => $request->phone_number,
                'password'              => bcrypt($request->password),
                'confirmation_code'     => $confirmationCode,
                'status'                =>  $request->has("role")&&$request->role==='providers'?'solicitud':'activo'
            ]);

            $message        =   [];
            $redirect       =   "AuthLoginScreen";

            if($request->has("role")){
                $role = Role::findByName($request->role, 'api');
                $user->assignRole($role);
                $redirect               =   "AuthRegisterBusinessSuccessScreen";
                $master_tables          =   MasterTable::where(['grupo'=>'email_register_business'])->first();
                $message["asunto"]      =   $master_tables->label;
                $message["message"]     =   $master_tables->description;
                //p($master_tables);
            }else{
                // Asignar rol
                $role = Role::findByName('clients', 'api');
                $user->assignRole($role);
                
            }
            
            // Enviar correo con el código de confirmación
            /**
             * Vamos a suspender el envío de email para evitar spam
             * app(EmailService::class)->sendEmailRegisterUser($user);
            */
            

            if (!empty($user->confirmation_code)) {
                unset($user->confirmation_code);
            }


            

            return response()->success(compact("user","redirect","message"), 'Usuario registrado exitosamente. Revisa tu correo para confirmar el registro.', 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }


    public function verifyCode(Request $request)
    {
        try {
            // Validar los datos enviados
            $request->validate([
                'email' => 'required|email',
                'confirmationCode' => 'required|digits:4', // Cambiar 'code' por 'confirmationCode'
            ]);

            // Buscar al usuario por email
            $user = User::where('email', $request->email)->first();

            //p([$user->confirmation_code,$request->confirmationCode,$request->email]);

            // Verificar si el usuario existe y si el código de confirmación es correcto
            if (!$user || $user->confirmation_code !== $request->confirmationCode) {
                return response()->error('Código de confirmación incorrecto o venció.', 400);
            }

            // Actualizar los campos del usuario
            $user->update([
                'email_verified_at' => now(),
                'confirmation_code' => null, // Limpia el código después de verificar
            ]);
            
            $redirect   =   false;

            if($user->hasRole("clients")){
                $redirect   =   "/question/anfitrion"; 
            }else if($user->hasRole("providers")){
                $redirect   =   "/question/proveedor"; 
            }

            $loginData              =   $this->loginRepository->generateTokenLogin($user);
            $user['permissions']    =   $loginData['permissions'];
            $is_admin               =   $loginData['is_admin'];
            $token                  =   $loginData['token'];            

            return response()->success(compact('user', 'token', 'is_admin',"redirect"), 'Correo electrónico verificado exitosamente.', 200);            

        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }



    public function login(Request $request)
    {
        try {
            $credentials = $request->only(['email', 'password']);
            if (!$token = auth('api')->attempt($credentials)) {
                return response()->error('Credenciales incorrectas', 401);
            }

            $loginData = $this->loginRepository->login($credentials);
            $user      = $loginData['user'];
            //p($user);
            // 🔹 Validar el estatus del usuario
            switch ($user["status"]) {
                case 'inactivo':
                    $message    =   'Tu cuenta está inactiva. Contacta con soporte.';
                    return response()->success(
                                                    compact('message'),
                                                    'No puedes iniciar sesión solicitud está en proceso.',
                                                    200
                                                );                    

                case 'solicitud':
                    $message    =   'Tu solicitud está en proceso. Te notificaremos pronto.';
                    return response()->success(
                                                    compact('message'),
                                                    'No puedes iniciar sesión solicitud está en proceso.',
                                                    200
                                                );
                case 'rechazado':
                    $message    =   'Tu registro ha sido rechazado. Contacta con soporte.';
                    return response()->success(
                                                    compact('message'),
                                                    'No puedes iniciar sesión solicitud está en proceso.',
                                                    200
                                                );
                    

                case 'activo':
                    // Continúa el flujo normal
                    break;

                default:
                    return response()->error('Estado de usuario no válido.', 403);
            }

            $user['permissions'] = $loginData['permissions'];
            $is_admin            = $loginData['is_admin'];

            if (!empty($loginData["redirect"])) {
                $user['redirect'] = $loginData['redirect'];
            }

            if ($loginData["usersSuscription"]) {
                $user["membership"] = [
                    "name"        => $loginData["usersSuscription"]->name,
                    "description" => $loginData["usersSuscription"]->description,
                    "start_date"  => $loginData["usersSuscription"]->start_date,
                    "end_date"    => $loginData["usersSuscription"]->end_date,                    
                ];
            }

            // Verifico si tiene características, si no tiene lo envío para welcome
            $userCharacteristics = UserCharacteristics::where("user_id", $user["id"])->get();

            return response()->success(
                compact('user', 'token', 'is_admin', 'userCharacteristics'),
                'Inicio de sesión exitoso',
                200
            );
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }


    public function me()
    {
        try {
            $user = auth('api')->user();
            return response()->success($user, 'Datos del usuario obtenidos exitosamente');
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function logout()
    {
        try {
            auth('api')->logout();
            return response()->success(null, 'Cierre de sesión exitoso');
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function refresh()
    {
        try {
            $token = JWTAuth::refresh();
            return $this->respondWithToken($token);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    protected function respondWithToken($token)
    {
        return response()->success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ], 'Token generado exitosamente');
    }
}
