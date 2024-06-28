var quill;
var documentoId;

function debounce(func, wait, immediate) {
	var timeout;
	return function () {
		var context = this,
			args = arguments;
		var later = function () {
			timeout = null;
			if (!immediate) func.apply(context, args);
		};
		var callNow = immediate && !timeout;
		clearTimeout(timeout);
		timeout = setTimeout(later, wait);
		if (callNow) func.apply(context, args);
	};
}

function exportarSEI() {
	var conteudoHtml = quill.root.innerHTML; // Obter o HTML do editor
	var nome = $("#nomeModelo").val();
	var numero = $("#numero").val();
	var tipo = $("#tipoConvenio option:selected").text();
	if(convenioId == undefined || convenioId == "" || convenioId == null) {
		convenioId = $("#selectConvenio").val();
	}
	if (nome == "" || nome.length < 3) {
		Swal.fire({
			title: "Informe o nome do modelo com pelo menos 3 caracteres!",
			icon: "warning",
		}).then(function () {
			$("#nomeModelo").focus();
		});
		return;
	}
	if (tipo == "") {
		Swal.fire({
			title: "Informe o tipo do modelo!",
			icon: "warning",
		}).then(function () {
			$("#tipoConvenio").focus();
		});
		return;
	}

	if (isNaN(numero) || !Number.isInteger(Number(numero))) {
		Swal.fire({
			title: "Informe um número válido!",
			icon: "warning",
		})
		.then(function () {
			$("#numero").focus();
		});
		return;
	}

	if(defaultModel == true && convenioId != "" && convenioId != null) {
		documentoId = "";
	}


	$.ajax({
		type: "POST",
		url: $("#base_url").val() + "documentos/salvar_modelo", // URL do script PHP para salvar o conteúdo
		data: {
			idConvenio: convenioId,
			nome: nome,
			tipo: tipo,
			text: conteudoHtml,
			idDocumento: documentoId,
			numero: numero
		},
		success: function (response) {
			Swal.fire({
				text: 'Me informe o ID do processo que deseja inserir o documento (Ex.: 21000.032483/2024-67 )',
				input: "text",
				showCancelButton: true,
				confirmButtonText: "Exportar",
				showLoaderOnConfirm: true,
				preConfirm: (processoId) => {
					return fetch(`${$("#base_url").val()}/automacoes?documentId=${documentoId}&processoId=${processoId}`)
						.then(response => {
							if (!response.ok) {
								throw new Error(response.statusText)
							}
							return response.json()
						})
						.catch(error => {
							Swal.showValidationMessage(`Request failed: ${error}`)
						})
				},
				allowOutsideClick: () => !Swal.isLoading()
			}).then((result) => {
				if (result.isConfirmed) {
					if (result.value.status === "success") {
						Swal.fire({
							title: "Success!",
							text: result.value.message,
							icon: "success"
						});
					} else {
						Swal.fire({
							title: "Erro!",
							text: "Erro ao inserir o documento no processo informado no SEI! " + result.value.message,
							icon: "error"
						});
					}
				}
			});
		},
		error: function (response) {
			Swal.fire({
				title: "Opss!",
				text: JSON.parse(response.responseText).msg,
				icon: "error",
			});
		},
	});
}

function salvarConteudo() {
	var conteudoHtml = quill.root.innerHTML; // Obter o HTML do editor
	var nome = $("#nomeModelo").val();
	var numero = $("#numero").val();
	var tipo = $("#tipoConvenio option:selected").text();
	if(convenioId == undefined || convenioId == "" || convenioId == null) {
		convenioId = $("#selectConvenio").val();
	}
	if (nome == "" || nome.length < 3) {
		Swal.fire({
			title: "Informe o nome do modelo com pelo menos 3 caracteres!",
			icon: "warning",
		}).then(function () {
			$("#nomeModelo").focus();
		});
		return;
	}
	if (tipo == "") {
		Swal.fire({
			title: "Informe o tipo do modelo!",
			icon: "warning",
		}).then(function () {
			$("#tipoConvenio").focus();
		});
		return;
	}

	if(defaultModel == true && convenioId != "" && convenioId != null) {
		documentoId = "";
	}

	$.ajax({
		type: "POST",
		url: $("#base_url").val() + "documentos/salvar_modelo", // URL do script PHP para salvar o conteúdo
		data: {
			idConvenio: convenioId,
			nome: nome,
			tipo: tipo,
			numero: numero,
			text: conteudoHtml,
			idDocumento: documentoId,
		},
		success: function (response) {
			Swal.fire({
				title: "Tudo certo!",
				text: response.msg,
				icon: "success",
			}).then(function () {
				if (response.hasOwnProperty("documentId")) {
					documentoId = response.documentId;
					window.location.href =
						$("#base_url").val() +
						"documentos/modelo/" +
						convenioId +
						"/" +
						documentoId;
				}
			});
		},
		error: function (response) {
			Swal.fire({
				title: "Opss!",
				text: JSON.parse(response.responseText).msg,
				icon: "error",
			});
		},
	});
}

function atualizarNumeroSugerido() {
	var tipoConvenio = $('#tipoConvenio').val();
	if(convenioId == undefined || convenioId == "" || convenioId == null) {
		convenioId = $("#selectConvenio").val();
	}

	if (tipoConvenio == "" || tipoConvenio == null) {
		return;
	}

	if (convenioId == "" || convenioId == null) {
		return;
	}

	$.ajax({
		url: $("#base_url").val() + "documentos/getSuggestDocumentoNumber",
		method: 'GET',
		data: {
			tipo: tipoConvenio,
			convenio: convenioId
		},
		success: function(response) {
			// Supondo que o retorno seja um inteiro
			$('#numero_sugerido').text(response);
		},
		error: function() {
			console.error('Erro ao obter o número sugerido.');
		}
	});
}

function selectLocalImage() {
	const input = document.createElement("input");
	input.setAttribute("type", "file");
	input.click();
	input.onchange = () => {
		const file = input.files[0];
		if (file) {
			uploadAndResizeImage(file);
		}
	};
}

function isValidHeightOrWidth(value) {
	if (!isNaN(value)) {
		return true;
	} else {
		return false;
	}
}

function uploadAndResizeImage(file) {
	const reader = new FileReader();
	var run = false;
	var _align;
	reader.onload = (e) => {
		const img = document.createElement("img");
		img.src = e.target.result;
		img.onload = async function () {
			if (run) {
				run = false;
				return;
			}
			run = true;
			const range = quill.getSelection();

			const { value: largura } = await Swal.fire({
				title: "Me informe a largura desejada da imagem em porcentagem (a altura será calculada automaticamente):",
				icon: "question",
				input: "range",
				inputLabel: "Largura da Imagem",
				inputAttributes: {
					min: "5",
					max: "100",
					step: "1"
				},
				inputValue: 100
			});

			if (!isValidHeightOrWidth(largura)) {
				Swal.fire({
					title: 'Error!',
					text: 'Largura inválida da imagem!',
					icon: 'error',
					confirmButtonText: 'Ok!'
				})
				return;
			}

			img.style.width = largura + '%';

			const { value: _usr_img_align } = await Swal.fire({
				title: "Me informe a alinhamento da imagem:",
				input: "radio",
				inputOptions: {
					left: "Esquerda",
					center: "Centro",
					right: "Direita"
				},
				inputValidator: (value) => {
					if (!value) {
						return "Você precisa escolher uma posição!";
					}
				}
			});

			_align = 'align-' + _usr_img_align;
			img.style.display = "block";
			img.style.marginLeft = _usr_img_align === "center" ? "auto" : "0";
			img.style.marginRight = _usr_img_align === "center" ? "auto" : "0";
			img.style.float =
				_usr_img_align === "left" ? "left" : _usr_img_align === "right" ? "right" : "none";
			usr_img_align = _usr_img_align;
			const formData = new FormData();

			formData.append("image", file);

			fetch($("#base_url").val() + "documentos/upload_img", {
				method: "POST",
				body: formData,
			})
			.then((response) => response.json())
			.then((result) => {
				if (result.status === "success") {
					console.log(result);
					console.log("HTML IMG: " + img.outerHTML);
					img.src = result.url;
					quill.insertEmbed(range.index, "pBlot", { html: img.outerHTML, cssClass: _align});
					_align = '';
					quill.setSelection(range.index + 1);
				} else {
					console.error(result.error);
				}
			})
			.catch((error) => {
				console.error("Error uploading image:", error);
			});			
		};
	};
	reader.readAsDataURL(file);
}

$(document).ready(function () {	
	var Embed = Quill.import("blots/embed");
	var convenioId = $("convenioId").val();

	if (typeof documento != "undefined") {
		documentoId = documento.id;
	}

	class TemplateMarker extends Embed {
		static create(value) {
			let node = super.create(value);
			node.setAttribute(
				"class",
				"badge rounded-pill text-dark bg-" + value.colour
			);
			node.setAttribute("data-marker", value.marker);
			node.setAttribute("data-title", value.title);
			node.innerHTML = value.title;
			return node;
		}

		static value(node) {
			return {
				marker: node.getAttribute("data-marker"),
				title: node.getAttribute("data-title"),
			};
		}
	}

	TemplateMarker.blotName = "TemplateMarker";
	TemplateMarker.tagName = "span";
	Quill.register("formats/TemplateMarker", TemplateMarker);

	var InlineBlot = Quill.import("blots/block");
	class ImageBlot extends InlineBlot {
		static create(data) {
			console.log(data);
			const node = super.create(data);
			node.setAttribute("data-src", data.src);
			node.setAttribute("src", data.src);
			node.setAttribute("style", data.style);
			console.log(node);
			return node;
		}
		static value(domNode) {
			const { src, style } = domNode.dataset;
			return { src, style };
		}
	}
	ImageBlot.blotName = "imageBlot";
	ImageBlot.className = "image-blot";
	ImageBlot.tagName = "img";
	Quill.register({ "formats/imageBlot": ImageBlot });

	let BlockEmbed = Quill.import("blots/block");
	class PBlot extends BlockEmbed {
		static create(value) {
			console.log(value);
			let node = super.create(value);
			node.innerHTML = value.html;
			node.className = value.cssClass;
			return node;
		}
		static value(node) {
			return {
				html: node.innerHTML,
				cssClass: node.className,
			};
		}
	}

	PBlot.blotName = "pBlot";
	PBlot.tagName = "p";

	Quill.register({ "formats/pBlot": PBlot });

	var toolbarOptions = [
		[{ align: '' }, { align: 'center' }, { align: 'right' }, { align: 'justify' }],
		["bold", "italic", "underline", "strike"],
		["blockquote", "code-block"],
		[
			{
				header: 1,
			},
			{
				header: 2,
			},
		],
		[
			{
				list: "ordered",
			},
			{
				list: "bullet",
			},
		],
		["link", "image", "video"],
		[
			{
				color: [],
			},
			{
				background: [],
			},
		],
		["clean"],
	];

	var options = {
		modules: {
			toolbar: toolbarOptions,
		},
		placeholder: "Digite o conteúdo de seu documento aqui...",
		theme: "snow",
	};

	quill = new Quill("#editor", options);

	if (typeof documento !== "undefined") {
		quill.disable();
		quill.root.innerHTML = documento.texto;
		quill.enable();
	}

	$(".ql-insertCustomTags").on("change", function () {
		let selectedOption = $(this).find(":selected");
		let range = quill.getSelection(true);

		quill.insertEmbed(
			range.index,
			"TemplateMarker",
			{
				colour: selectedOption.data("colour"),
				marker: selectedOption.data("marker"),
				title: selectedOption.data("title"),
			},
			Quill.sources.USER
		);

		quill.insertText(range.index + 1, " ", Quill.sources.USER);
		quill.setSelection(range.index + 2, Quill.sources.SILENT);
		$(this).val("Selecione aqui as variáveis de seu documento...");
	});

	quill.getModule("toolbar").addHandler("image", () => {
		selectLocalImage();
	});

	$("#numeroConvenio").keyup(
		debounce(function () {
			numeroConvenio = $("#numeroConvenio").val();
			selectConvenio = document.getElementById("selectConvenio");

			if (numeroConvenio.length < 3 || numeroConvenio == "") {
				return;
			}

			$.ajax({
				type: "GET",
				url: $("#base_url").val() + "documentos/searchConvenios",
				data: {
					numeroConvenio: numeroConvenio
				},
				dataType: 'json',
				success: function (response) {		
					console.log(response);
					$('#selectConvenio').empty()
					selectConvenio.style.display = "block";			
					$('#selectConvenio').append($('<option data-hidden="true">').text("Selecione um convênio..."));
                    $.each(response, function (index, element) {
                        $('#selectConvenio').append($('<option>', {
                            value: element.val,
                            text: element.text
                        }));
                    });				
				},
				error: function (response) {
					Swal.fire({
						title: "Opss!",
						text: 'Tivemos um erro ao consultar o convenio, tente novamente mais tarde!',
						icon: "error",
					});
				},
			});
		}, 650)
	);

	$("#selectConvenio").change(function(){
		let selectConvenio = $(this).val();
		$("#numeroConvenio").val(selectConvenio);
	});

	$('#tipoConvenio').on('change', function() {
        atualizarNumeroSugerido();
    });

	atualizarNumeroSugerido();

});
