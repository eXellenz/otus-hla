/**
*	JS | OTUS HLA | UTF8 | js/scriptCheckForm.js
*	Home work
*	eXellenz (eXellenz@inbox.ru)
*	2024-02-27
*/
var elemRegForm			= $('#reg-form');
var elemRegButton		= $('#reg-button');
var elemRegLogin		= $('#reg-login');
var elemRegPassword		= $('#reg-password');
var elemRegName			= $('#reg-name');
var elemRegLastname		= $('#reg-lastname');
var elemRegAge			= $('#reg-age');
var elemRegGender		= $('#reg-gender');
var elemRegCity			= $('#reg-city');
var elemRegAbout		= $('#reg-about');
var elemAuthForm		= $('#auth-form');
var elemAuthButton		= $('#auth-button');
var elemAuthLogin		= $('#auth-login');
var elemAuthPassword	= $('#auth-password');
var elemSearch			= $('#search-user');
var elemSearchButton	= $('#search-button');
var elemPostForm		= $('#post-form');
var elemPostTitle		= $('#post-title');
var elemPostText		= $('#post-text');
var elemPostButton		= $('#post-button');

$(document).ready(function()
{
	if (elemRegForm)
	{
		elemRegForm.submit(ValidateRegFormSubmit);
	}
	if (elemAuthForm)
	{
		elemAuthForm.submit(ValidateAuthFormSubmit);
	}
	if (elemPostForm)
	{
		elemPostForm.submit(ValidatePostFormSubmit);
	}
	if (elemSearchButton)
	{
		elemSearchButton.prop('disabled', true);
		$(document).on('change input keypress paste keyup', '#search-user', CatchSearchUser);
	}
});

ValidateRegFormSubmit = function(e)
{
	e.preventDefault();

	let validateIsOk	= true;
	let	formMessage		= '';

	if (!$.trim(elemRegLogin.val()))
	{
		validateIsOk = false;
		formMessage += "Укажите Логин\r\n";
	}
	if (!$.trim(elemRegPassword.val()))
	{
		validateIsOk = false;
		formMessage += "Укажите Пароль\r\n";
	}
	if (!$.trim(elemRegName.val()))
	{
		validateIsOk = false;
		formMessage += "Укажите Имя\r\n";
	}
	if (!$.trim(elemRegLastname.val()))
	{
		validateIsOk = false;
		formMessage += "Укажите Фамилия\r\n";
	}
	if (!$.trim(elemRegAge.val()))
	{
		validateIsOk = false;
		formMessage += "Укажите Возраст\r\n";
	}
	if (!$.trim(elemRegGender.val()))
	{
		validateIsOk = false;
		formMessage += "Укажите Пол\r\n";
	}
	if (!$.trim(elemRegCity.val()))
	{
		validateIsOk = false;
		formMessage += "Укажите Город\r\n";
	}
	if (!$.trim(elemRegAbout.val()))
	{
		validateIsOk = false;
		formMessage += "Укажите Интересы\r\n";
	}

	if (validateIsOk == false)
	{
		alert(formMessage);
	}
	else
	{
		elemRegButton.prop('disabled', true);
		elemRegForm.unbind('submit').submit();
	}
}

ValidateAuthFormSubmit = function(e)
{
	e.preventDefault();

	let validateIsOk	= true;
	let	formMessage		= '';

	if (!$.trim(elemAuthLogin.val()))
	{
		validateIsOk = false;
		formMessage += "Укажите Логин\r\n";
	}
	if (!$.trim(elemAuthPassword.val()))
	{
		validateIsOk = false;
		formMessage += "Укажите Пароль\r\n";
	}

	if (validateIsOk == false)
	{
		alert(formMessage);
	}
	else
	{
		elemAuthButton.prop('disabled', true);
		elemAuthForm.unbind('submit').submit();
	}
}

ValidatePostFormSubmit = function(e)
{
	e.preventDefault();

	let validateIsOk	= true;
	let	formMessage		= '';

	if (!$.trim(elemPostTitle.val()))
	{
		validateIsOk = false;
		formMessage += "Укажите Тему\r\n";
	}
	if (!$.trim(elemPostText.val()))
	{
		validateIsOk = false;
		formMessage += "Укажите Сообщение\r\n";
	}

	if (validateIsOk == false)
	{
		alert(formMessage);
	}
	else
	{
		elemPostButton.prop('disabled', true);
		elemPostForm.unbind('submit').submit();
	}
}

CatchSearchUser = function()
{
	let strSearch	= elemSearch.val();
	
	if (strSearch.length >= 3)
	{
		elemSearchButton.prop('disabled', false);
	}
	else
	{
		elemSearchButton.prop('disabled', true);
	}
}
