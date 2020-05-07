$(function(){

	$.validator.addMethod('strongPassword', function(value,element){
        return this.optional(element)|| value.length >= 8 && /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/.test(value)
    }, 'Mật khẩu của bạn pải có ít nhất 8 kí tự, 1 hoa , 1 thường, 1 số và ko có kí tự đặc biệt!');

    // BUG: Nó loại bỏ cả kí tự có dấu
    $.validator.addMethod('noSpecialCharacter', function(value,element){
    	return this.optional(element) || /^[_A-z0-9]*((-|\s)*[_A-z0-9])*$/.test(value)
    }, 'Không dùng kí tự đặc biệt!');

     $.validator.addMethod('strongEmail', function(value,element){
     	// let n = value.indexOf("@");
     	// let str = value.subtr(0,n);
     	console.log(value.indexOf('@'));
    	return this.optional(element) || value.indexOf('@') >= 5
    }, 'email phải có tối thiểu 5 kí tự!');

    $("#register-form").validate({
        rules: {
            name: {
            	required: true,
            	// noSpecialCharacter: true
            },
            email: {
                // minlength: 5,
                required: true,
                email: true,
                strongEmail: true,
            },
            pwd: {
                required: true,
                strongPassword: true
            },
            confirm_pwd: {
                required: true,
                equalTo: "#pwd"
            }
        },
        messages: {
            name: {
                required: "Hãy điền tên đăng nhập!",
            },
            email: {
            	required: "Hãy điền email!",
            	email: "Không đúng định đạng email! <br>email có dạng: abc@gmail.com,def@yahoo.com,...",
            },
            pwd: {
                required: "Vui lòng điền mật khẩu!",
            },
            confirm_pwd: {
            	required: "Vui lòng nhập lại mật khẩu!",
                equalTo: "Mật khẩu nhập lại ko khớp!",
            }
        }
    });
});