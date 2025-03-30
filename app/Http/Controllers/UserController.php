<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Helper\JWTToken;
use App\Mail\OTPMail;
use Illuminate\Support\Facades\Mail;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function Login()
    {
        return Inertia::render('loginPage');
    }

    public function Registration()
    {
        return Inertia::render('RegistrationPage');
    }

    public function SendOtpPage()
    {
        return Inertia::render('SendOtpPage');
    }

    public function VerifyOtpPage()
    {
        return Inertia::render('VerifyOtpPage');
    }
    public function ResetPasswordPage()
    {
        return Inertia::render('ResetPasswordPage');
    }

    //user registration
    public function UserRegistration(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
                'mobile' => 'required',
            ]);
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'mobile' => $request->input('mobile'),
            ]);

           
            $data = ['message' => 'User create successfully', 'status' => true, 'error' => ''];
            return redirect('/login')->with($data);
        } catch (Exception $e) {
         
            $data = ['message' => 'User create fail', 'status' => false, 'error' => ''];
            return redirect('/register')->with($data);
        }
    } //end registration

    //user login
    public function UserLogin(Request $request)
    {
        $count = User::where('email', '=', $request->input('email'))->where('password', '=', $request->input('password'))->select('id')->first();

        if ($count !== null) {
            $email = $request->input('email');
            $user_id = $count->id;
            $request->session()->put('email', $email);
            $request->session()->put('user_id', $user_id);
            $data = ['message' => ' login successfully', 'status' => true, 'error' => ''];
            return redirect('/DashboardPage')->with($data);
        
        } else {
            $data = ['message' => 'user login failed', 'status' => false, 'error' => ''];
            return redirect('/login')->with($data);
        }
    } //end login

    //LoginVerifyPage
    public function LoginVerifyPage(Request $request)
    {
        $user = $request->header('email');
        return response()->json(
            [
                'status' => 'success',
                'message' => 'user login successfully',
                'user' => $user,
            ],
            200,
        );
    } //end LoginVerifyPage

    public function DashboardPage(Request $request)
    {
      
        return Inertia::render('DashboardPage');
    }

    //user logout
    public function UserLogout(Request $request)
    {
        
        $request->session()->forget('email');
        $request->session()->forget('user_id');
        $data = ['message' => 'logout successfully', 'status' => true, 'error' => ''];
        return redirect('/login')->with($data);
    } //end user logout

    //OTP Send
    public function SendOTP(Request $request)
    {
        $email = $request->input('email');
        $otp = rand(1000, 9999);
        $count = User::where('email', $email)->count();
        if ($count == 1) {
          
            User::where('email', $email)->update(['otp' => $otp]);
            $request->session()->put('email', $email);
         
            $data = ['message' => '4 Digit {$otp} OTP send successfully', 'status' => false, 'error' => ''];
            return redirect('/verify-otp')->with($data);
        } else {
            
            $data = ['message' => 'Unauthorized', 'status' => false, 'error' => ''];
            return redirect('/register')->with($data);
        }
    } //end OTP Send

    //VerifyOTP
    public function VerifyOTP(Request $request)
    {
        $email = $request->session()->get('email');
        $otp = $request->input('otp');

        $count = User::where('email', $email)->where('otp', $otp)->count();

        if ($count == 1) {
            User::where('email', $email)->update(['otp' => 0]);
            $request->session()->put('otp_verify', 'yes');
            $data = ['message' => 'OTP Verify Successfully', 'status' => true, 'error' => ''];
            return redirect('/reset-password')->with($data);
        } else {
            $data = ['message' => 'Unauthorized', 'status' => false, 'error' => ''];
            return redirect('/login')->with($data);
        }
    } //end VerifyOTP

    //ResetPassword
    public function ResetPassword(Request $request)
    {
        try {
            $email = $request->session()->get('email','default');
            $password = $request->input('password');
            $otp_verify = $request->session()->get('otp_verify','default');
            if($otp_verify=='yes'){
                User::where('email', '=', $email)->update(['password' => $password]);
                $request->session()->flush();
                $data=['message' => 'Password Reset Successfully', 'status' => true, 'error' => ''];
                return redirect('/login')->with($data);
            }else{
        $data = ['message' => 'Request Fail', 'status' => false, 'error' => ''];
        return redirect('/restPassword')->with($data);
            }

           
        } catch (Exception $e) {
            $data = ['message' => $e->getMessage() . '', 'status' => false, 'error' => ''];
            return redirect('/restPassword')->with($data);
        }
    } //end ResetPassword
}
