Hello {{$user->name}}
Thank you for create an account. Please verify your email using the link:
{{route('verify', $user->verification_token)}}