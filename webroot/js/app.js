window.addEventListener("load", function(){
	var modal = document.getElementById("myModal");

	var span = document.getElementsByClassName("close")[0];

	var divClone; 

	openModal = function(e) {
		modal.style.display = "block";

		form = $("#reportForm").html();

		var type = $(e).attr("data-type");
		var id = $(e).attr("data-id");

		$("#reportForm").append('<input type="hidden" value="' + type + '" name="type">');
		$("#reportForm").append('<input type="hidden" value="' + id + '" name="id">');

		divClone = $("#reportForm").clone();

		$("#reportForm").submit(function(event) {
            event.preventDefault();
  			event.stopImmediatePropagation();

            var $form = $(this),
                data = $form.serialize(),
                url = $form.attr('action');

            var posting = $.post(url, data);

            posting.done(function(data) {
            	console.log(data);
            	$('#reportForm').hide();
            	$("#myModal .modal-body").append('<div class="success-msg"><i class="fa fa-check"></i>' + JSON.parse(data).result + '</div>');
            });

            posting.fail(function(data) {
            	console.log(data);
            	$('#reportForm').hide();
            	$("#myModal .modal-body").append('<div class="success-msg"><i class="fa fa-check"></i>' + JSON.parse(data).result + '</div>');
            });
        });
	}

	span.onclick = function() {
		if ($("#reportForm")) {
			$("#reportForm input[name=type]").remove();
			$("#reportForm input[name=id]").remove();
			$('#reportForm').trigger("reset");
			$('#myModal .modal-body .success-msg').remove();
			$('#reportForm').show();
		}

	  	modal.style.display = "none";
	}

	window.onclick = function(event) {
		if (event.target == modal) {
			if ($("#reportForm")) {
				$("#reportForm input[name=type]").remove();
				$("#reportForm input[name=id]").remove();
				$('#reportForm').trigger("reset");
				$('#myModal .modal-body .success-msg').remove();
				$('#reportForm').show();
			}
		
		    modal.style.display = "none";
		}
	}
});