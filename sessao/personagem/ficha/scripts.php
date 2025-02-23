<script>
	<?php if ($edit){?>
    let changingtimer, x, y;                //timer identifier
    const donetimer = 1500;

    function deletar(id, nome, tipo) {
        confirmar("Tem certeza?", "Essa ação não poderá ser desfeita.").then((r) => {
            if (r) {
                $.post({
                    url: "?token=<?=$fichat?>",
                    data: {did: id, status: tipo,},
                    complete: () => {
                        window.location.reload();
                    },
                });
            }
        })
    }//Deletar Arma
    function editarma(id) {
        $('#editarmatitle, #enome').val($("#armaid" + id + " .arma").text());
        $('#etipo').val($("#armaid" + id + " .tipo").text());
        $('#eataque').val($("#armaid" + id + " .ataque").text());
        $('#ealcance').val($("#armaid" + id + " .alcance").text());
        $('#edano').val($("#armaid" + id + " .dano").text());
        $('#ecritico').val($("#armaid" + id + " .critico").text());
        $('#emargem').val($("#armaid" + id + " .margem").text());
        $('#erecarga').val($("#armaid" + id + " .recarga").text());
        $('#eespecial').val($("#armaid" + id + " .especial").text());
        $('#editarmaid').val(id);
    }// Editar Arma
    function edititem(id) {
        $('#edititemtitle, #enom').val($("#itemid" + id + " .nome").text());
        $('#edes').val($("#itemid" + id + " .desc").text());
        $('#epes').val($("#itemid" + id + " .espaco").text());
        $('#epre').val($("#itemid" + id + " .prestigio").text());
        $('#edititid').val(id);
    }// Editar Item
    function cleanedit() {
        $('#deletarid,#deletarnome,#deletarstatus, #enome,#etipo,#eataque,#ealcance,#edano,#ecritico,#erecarga,#eespecial,#editarmaid,#enom,#edes,#epes,#epre,#edititid,#anom,#ades,#apes,#apre,#additemid').val('');
    }// Limpar modal edições
    function percent(max, min = 0) {
        if ((max === 0 && min === 0) || max === 0) {
            return 0;
        }
        const p = (max / min) * 100;
        if (p > 100) {
            return 100;
        } else {
            return p;
        }
    }

    function subtimer() {
        clearTimeout(changingtimer);
        changingtimer = setTimeout(subsaude, donetimer);
    }

    function updtsaude(valor, type) {
        let atual = type + 'a';
        let maxim = type;
        let saude = parseInt($("#saude ." + atual).val()) + valor;
        $("#saude ." + atual).val(saude);
        let per = parseInt($("#saude ." + maxim).val());
        if ($('#saude .pva').val() < <?=$minimo_PVA?>) {
            $('#saude .pva').val(<?=$minimo_PVA?>);
        }
        if ($('#saude .sana').val() < <?=$minimo_SANA?>) {
            $('#saude .sana').val(<?=$minimo_SANA?>);
        }
        if ($('#saude .pea').val() < <?=$minimo_PEA?>) {
            $('#saude .pea').val(<?=$minimo_PEA?>);
        }

        if ($('#saude .pva').val() > <?=$pv + $maximo_PVA?>) {
            $('#saude .pva').val(<?=$pv + $maximo_PVA?>);
        }
        if ($('#saude .sana').val() > <?=$san + $maximo_SANA?>) {
            $('#saude .sana').val(<?=$san + $maximo_SANA?>);
        }
        if ($('#saude .pea').val() > <?=$pe + $maximo_PEA?>) {
            $('#saude .pea').val(<?=$pe + $maximo_PEA?>);
        }

        $("#barra" + atual).css('width', percent(saude, per) + '%');
        subtimer();
    }

    function subsaude() {
        let data = $('#saude :input').serialize();
        if ($('#morrendo').is(":checked")) {
            x = 1;
        } else {
            x = 0;
        }
        data += '&status=usau&mor=' + x;
        console.log(data);
        $.post({
            url: '?token=<?=$fichat?>',
            dataType: 'json',
            data: data,
            complete: (d) => {
                console.log(d)
            },
        }).done(function (data) {
            console.log(data);
            const msg = {};
            if ($('#combate').is(":checked")) {
                y = true;
            } else {
                y = false;
            }
            msg["vida"] = data;
            msg["vida"]["combate"] = y;
            msg["ficha"] = '<?=$fichat?>';
            console.log(msg.vida.pv);
            $('#saude .pv').val(msg.vida.pv);
            $('#saude .san').val(msg.vida.san);
            $('#saude .pe').val(msg.vida.pe);
            console.log($('#saude .pe').val());
            updatefoto()
            socket.emit('<?=$missao_token ?: $fichat?>', msg);
        });
    }

    function editritual(i,id) {
        let editritual_modal = new bootstrap.Modal($("#editritual"));
        editritual_modal.show();
        $("#editritual .url").val($("#but-ritual-"+i+" .foto").prop("src"));
        $("#editritual .foto").prop("src",$("#but-ritual-"+i+" .foto").prop("src"));
        $("#editritual .ritual").val($("#but-ritual-"+i+" .nome").html());
        $("#editritual .elemento").val($("#but-ritual-"+i+" .elemento").html());
        $("#editritual .circulo").val($("#but-ritual-"+i+" .circulo").html());
        $("#editritual .conjuracao").val($("#but-ritual-"+i+" .conjuracao").html());
        $("#editritual .alcance").val($("#but-ritual-"+i+" .alcance").html());
        $("#editritual .alvo").val($("#but-ritual-"+i+" .alvo").html());
        $("#editritual .duracao").val($("#but-ritual-"+i+" .duracao").html());
        $("#editritual .resistencia").val($("#but-ritual-"+i+" .resistencia").html());
        $("#editritual .desc").val($("#but-ritual-"+i+" .desc").html());
        $("#editritual .normal").val($("#but-ritual-"+i+" .normal").attr("data-dado"));
        $("#editritual .discente").val($("#but-ritual-"+i+" .discente").attr("data-dado"));
        $("#editritual .verdadeiro").val($("#but-ritual-"+i+" .verdadeiro").attr("data-dado"));
        $("#editritual .did").val(id);
        console.log($("#but-ritual-"+i+" .verdadeiro"));
    }


    function updatefoto() {
        let pv = parseInt($('#saude .pv').val());
        let pva = parseInt($('#saude .pva').val());
        let san = parseInt($('#saude .san').val());
        let sana = parseInt($('#saude .sana').val());

        if (pva <= 0) {
            $("#fotopersonagem").attr("src", "<?=$urlphotomor;?>");
            console.log("morrendo")
        } else if (sana <= 0) {
            if (percent(pva, pv) < 50) {
                console.log("enlouquecendo + ferido")
                $("#fotopersonagem").attr("src", "<?=$urlphotoef;?>");
            } else {
                console.log("enlouquecendo")
                $("#fotopersonagem").attr("src", "<?=$urlphotoenl;?>");
            }
        } else if (percent(pva, pv) < 50) {
            console.log("ferido")
            $("#fotopersonagem").attr("src", "<?=$urlphotofer;?>");
        } else {
            console.log("normal")
            $("#fotopersonagem").attr("src", "<?=$urlphoto;?>");
        }
    }

    $(document).ready(function () {
        $('#morrendo,#combate').change(function () {
            subtimer();
        })

        $("#editritual input.url").on("input", ()=>{
            $this = $("#editritual input.url");

            let src = $this.val().trim();
            if (!src.match("^https?://(?:[a-z\-]+\.)+[a-z]{2,6}(?:/[^/#?]+)+\.(?:jpg|png|jpeg|webp)$") || src == "") {
                $this.addClass("is-invalid").addClass("is-valid");
                $("#editritual .return").html("Precisa ser HTTPS, e Terminar com com extensão de imagem(jpg,png,...)!");
                $('#editritual img.foto').prop("src","https://fichasop.com/assets/img/desconhecido.webp");
                return false;
            } else {
                $this.addClass("is-valid").removeClass("is-invalid");
                $("#editritual .return").html("");
                $('#editritual img.foto').prop("src",src);
            }


        })

        $('#editritual select.rituais').change(() => {
            let $foto;
            console.log($('#editritual select.rituais').val())
            switch (parseInt($('#editritual select.rituais').val())) {
                default:
                    $foto = '';
                    break;
                case 2:
                    $foto = 'https://fichasop.com/assets/img/desconhecido.webp';
                    break;
                case 3:
                    $foto = 'https://fichasop.com/assets/img/Simbolo_Amaldicoar_Tecnologia.webp';
                    break;
                case 4:
                    $foto = 'https://fichasop.com/assets/img/Simbolo_Assombracao_Forcada.webp';
                    break;
                case 5:
                    $foto = 'https://fichasop.com/assets/img/Simbolo_Camuflagem.webp';
                    break;
                case 6:
                    $foto = 'https://fichasop.com/assets/img/Simbolo_Cicatrizacao_Acelerada.webp';
                    break;
                case 7:
                    $foto = 'https://fichasop.com/assets/img/Simbolo_Coincidencia_Forcada.webp';
                    break;
                case 8:
                    $foto = 'https://fichasop.com/assets/img/Simbolo_Compreensao_Paranormal.webp';
                    break;
                case 9:
                    $foto = 'https://fichasop.com/assets/img/Simbolo_Comunicacao_com_Espiritos.webp';
                    break;
                case 10:
                    $foto = 'https://fichasop.com/assets/img/Simbolo_da_Dama_de_Sangue.webp';
                    break;
                case 11:
                    $foto = 'https://fichasop.com/assets/img/Simbolo_decadenzia.webp';
                    break;
                case 12:
                    $foto = 'https://fichasop.com/assets/img/Simbolo_Derreter_Criaturas_De_Sangue.webp';
                    break;
                case 13:
                    $foto = 'https://fichasop.com/assets/img/Simbolo_Descarnar.webp';
                    break;
                case 14:
                    $foto = 'https://fichasop.com/assets/img/Simbolo_Destruicao.webp';
                    break;
                case 15:
                    $foto = 'https://fichasop.com/assets/img/Simbolo_Dissipar_Espiritos.webp';
                    break;
                case 16:
                    $foto = 'https://fichasop.com/assets/img/Simbolo_Invocar_Nevoa.webp';
                    break;
                case 17:
                    $foto = 'https://fichasop.com/assets/img/Simbolo_Leitura_Psiquica.webp';
                    break;
                case 18:
                    $foto = 'https://fichasop.com/assets/img/Simbolo_odio_Incontrolavel.webp';
                    break;
                case 19:
                    $foto = 'https://fichasop.com/assets/img/Simbolo_Papel_Graduacao.webp';
                    break;
                case 20:
                    $foto = 'https://fichasop.com/assets/img/Simbolo_Paralisia_Anormal.webp';
                    break;
                case 21:
                    $foto = 'https://fichasop.com/assets/img/Simbolo_Passagem_de_Conhecimento.webp';
                    break;
                case 22:
                    $foto = 'https://fichasop.com/assets/img/Simbolo_Pavor_Anormal.webp';
                    break;
                case 23:
                    $foto = 'https://fichasop.com/assets/img/Simbolo_Reacao.webp';
                    break;
                case 24:
                    $foto = 'https://fichasop.com/assets/img/Simbolo_Ritual_Espelho.webp';
                    break;
                case 25:
                    $foto = 'https://fichasop.com/assets/img/Simbolo_Sentir_Atraves_dois_em_um.webp';
                    break;
                case 26:
                    $foto = 'https://fichasop.com/assets/img/Simbolo_Sugada_Mortal.webp';
                    break;
                case 27:
                    $foto = 'https://fichasop.com/assets/img/simbolo_transcender.webp';
                    break;
            }
            $("#editritual input.url").val($foto);

        })
        $('#addritual .selectosimbolo').change(function () {
            console.log("ok")
            let $foto = $('#addritual .selectosimbolo').val()

            if ($foto === "Customizada") {
                $("#simbolourl input").attr("readonly", false)
            } else {
                $("#simbolourl input").attr("readonly", true);
                switch ($foto) {
                    default:
                        $foto = 'https://fichasop.com/assets/img/desconhecido.webp';
                        break;
                    case '3':
                        $foto = 'https://fichasop.com/assets/img/Simbolo_Amaldicoar_Tecnologia.webp';
                        break;
                    case '4':
                        $foto = 'https://fichasop.com/assets/img/Simbolo_Assombracao_Forcada.webp';
                        break;
                    case '5':
                        $foto = 'https://fichasop.com/assets/img/Simbolo_Camuflagem.webp';
                        break;
                    case '6':
                        $foto = 'https://fichasop.com/assets/img/Simbolo_Cicatrizacao_Acelerada.webp';
                        break;
                    case '7':
                        $foto = 'https://fichasop.com/assets/img/Simbolo_Coincidencia_Forcada.webp';
                        break;
                    case '8':
                        $foto = 'https://fichasop.com/assets/img/Simbolo_Compreensao_Paranormal.webp';
                        break;
                    case '9':
                        $foto = 'https://fichasop.com/assets/img/Simbolo_Comunicacao_com_Espiritos.webp';
                        break;
                    case '10':
                        $foto = 'https://fichasop.com/assets/img/Simbolo_da_Dama_de_Sangue.webp';
                        break;
                    case '11':
                        $foto = 'https://fichasop.com/assets/img/Simbolo_decadenzia.webp';
                        break;
                    case '12':
                        $foto = 'https://fichasop.com/assets/img/Simbolo_Derreter_Criaturas_De_Sangue.webp';
                        break;
                    case '13':
                        $foto = 'https://fichasop.com/assets/img/Simbolo_Descarnar.webp';
                        break;
                    case '14':
                        $foto = 'https://fichasop.com/assets/img/Simbolo_Destruicao.webp';
                        break;
                    case '15':
                        $foto = 'https://fichasop.com/assets/img/Simbolo_Dissipar_Espiritos.webp';
                        break;
                    case '16':
                        $foto = 'https://fichasop.com/assets/img/Simbolo_Invocar_Nevoa.webp';
                        break;
                    case '17':
                        $foto = 'https://fichasop.com/assets/img/Simbolo_Leitura_Psiquica.webp';
                        break;
                    case '18':
                        $foto = 'https://fichasop.com/assets/img/Simbolo_odio_Incontrolavel.webp';
                        break;
                    case '19':
                        $foto = 'https://fichasop.com/assets/img/Simbolo_Papel_Graduacao.webp';
                        break;
                    case '20':
                        $foto = 'https://fichasop.com/assets/img/Simbolo_Paralisia_Anormal.webp';
                        break;
                    case '21':
                        $foto = 'https://fichasop.com/assets/img/Simbolo_Passagem_de_Conhecimento.webp';
                        break;
                    case '22':
                        $foto = 'https://fichasop.com/assets/img/Simbolo_Pavor_Anormal.webp';
                        break;
                    case '23':
                        $foto = 'https://fichasop.com/assets/img/Simbolo_Reacao.webp';
                        break;
                    case '24':
                        $foto = 'https://fichasop.com/assets/img/Simbolo_Ritual_Espelho.webp';
                        break;
                    case '25':
                        $foto = 'https://fichasop.com/assets/img/Simbolo_Sentir_Atraves_dois_em_um.webp';
                        break;
                    case '26':
                        $foto = 'https://fichasop.com/assets/img/Simbolo_Sugada_Mortal.webp';
                        break;
                    case '27':
                        $foto = 'https://fichasop.com/assets/img/simbolo_transcender.webp';
                        break;
                }
                $("#addritual #simbolourl input").val($foto)
                updateritualfoto($("#addritual #simbolourl input"));
            }
        })

        $('#portrait').change(function () {
            if ($('#portrait').is(":checked")) {
                socket = io.connect('https://portrait.fichasop.com', {reconnectionDelay: 2500, transports: ['websocket', 'polling', 'flashsocket']});
                socket.emit('create', '<?=$missao_token ?: $fichat?>');
                socket.emit('<?=$missao_token ?: $fichat?>', {auth: '<?=$fichat?>'});
            } else {
                socket.disconnect();
            }
        })


        $("form").submit(function (event) {
            $(this).addClass('was-validated');
            if (!$(this).isValid()) {
                event.preventDefault()
                event.stopPropagation()
            } else {
                event.preventDefault();
                $.post({
                    url: '?token=<?=$fichat?>',
                    data: $(this).serialize(),
                    complete: (d) => {
                        console.log(d)
                    },
                }).done(function (data) {
                    location.reload();
                })
            }
        })// Enviar qualquer formulario via jquery


        $("#saude .dblclick input").dblclick(function () {
            $(this).attr('readonly', false).toggleClass('border-0');
        }).focusout(function () {
            let attr = $(this).attr('readonly');
            if (typeof attr !== 'undefined' && attr !== false) {
                $(this).attr('readonly', true)
            } else {
                $(this).attr('readonly', true).toggleClass('border-0')
            }
            updtsaude();
        })

        $("button, input:checkbox").on("click", function () {
            $(this).blur();
        })


        $('#editfoto .foto-perfil').on('input', function () {
            console.log("escrito")
            var src = $(this).val();
            if (!src.match("^https?://(?:[a-z\-]+\.)+[a-z]{2,6}(?:/[^/#?]+)+\.(?:jpg|png|jpeg|webp|gif)$") || src == "") {
                $("#editfoto .return").html("<div class='alert alert-danger m-2'><i class='fat fa-x'></i> Precisa iniciar com HTTPS e terminar com .PNG|.WEBP|.JPG|.GIF!</div>");
                $('#editfoto .preview img').prop("src", '').parent().hide();
                return false;
            } else {
                $("#editfoto .return").html("");
                $('#editfoto .preview img').prop("src", src).parent().show();
            }

        })

        $('#editfoto .selector').change(function () {
            console.log($(this).val())
            let foto;
            switch (parseInt($(this).val())) {
                case 1:
                    foto = 'https://fichasop.com/assets/img/Man.webp';
                    break;
                case 2:
                    foto = 'https://fichasop.com/assets/img/Woman.webp';
                    break;
                case 3:
                    foto = 'https://fichasop.com/assets/img/Mauro.webp';
                    break;
                case 4:
                    foto = 'https://fichasop.com/assets/img/Maya.webp';
                    break;
                case 5:
                    foto = 'https://fichasop.com/assets/img/Bruna.webp';
                    break;
                case 6:
                    foto = 'https://fichasop.com/assets/img/Leandro.webp';
                    break;
                case 7:
                    foto = 'https://fichasop.com/assets/img/Jaime.webp';
                    break;
                case 8:
                    foto = 'https://fichasop.com/assets/img/Aniela.webp';
                    break;
            }

            $("#editfoto .foto-perfil").val(foto)
            $("#editfoto .return").html("");
            $('#editfoto .preview img').prop("src", foto).parent().show();

        })


        $('.teedfa').on('input', function () {
            thisid = $(this).attr("id");
            var src = $('#' + thisid + ' input.simbolourl').val();
            if (!src.match("^https?://(?:[a-z\-]+\.)+[a-z]{2,6}(?:/[^/#?]+)+\.(?:jpg|png|jpeg|webp)$") || src == "") {
                $('#' + thisid + ' div.warningsimbolo').html("Precisa ser HTTPS, e Terminar com com extensão de imagem(jpg,png,...)!");
                $('#' + thisid + ' div.prevsimbolo').html(' <img src="/assets/img/desconhecido.webp" width="200" height="200" alt="Ritual">');
                return false;
            } else {
                $('#' + thisid + ' div.warningsimbolo').html("");
                $('#' + thisid + ' div.prevsimbolo').html('<img src="' + src + '" width="200" height="200" alt="Ritual">');
            }

        }).change(function () {
            thisid = $(this).attr("id");
            let fotovalor = $('#' + thisid + ' select.fotosimbolo').val()
            if (fotovalor == '2') {
                $('#' + thisid + ' .divfotosimbolourl').show();
                $('#' + thisid + ' input').attr("disabled", false)
            } else {
                $('#' + thisid + ' .divfotosimbolourl').hide();
                $('#' + thisid + ' input').attr("disabled", true)
            }
        })

        $('#addarmainvswitch').on('click', function () {
            if ($(this).is(":checked")) {
                $('#addarmainv input[type=text], #addarmainv input[type=number]').attr('disabled', false);
            } else {
                $('#addarmainv input[type=text], #addarmainv input[type=number]').attr('disabled', true);
            }
        }) //Ativar/Desativar Inventario em adicionar arma

        $('#card_principal .popout').on('click', function () {
            window.open("/sessao/personagem?popout=principal&token=<?=$fichat?>", "yyyyy", "width=480,height=360,resizable=no,toolbar=no,menubar=no,location=no,status=no");
            return false;
        })
        $('#card_dados .popout').on('click', function () {
            window.open("/sessao/personagem?popout=dados&token=<?=$fichat?>", "yyyyy", "width=480,height=360,resizable=no,toolbar=no,menubar=no,location=no,status=no");
            return false;
        })
        $('#card_atributos .popout').on('click', function () {
            window.open("/sessao/personagem?popout=atributos&token=<?=$fichat?>", "yyyyy", "width=480,height=360,resizable=no,toolbar=no,menubar=no,location=no,status=no");
            return false;
        })
        $('#card_inventario .popout').on('click', function () {
            window.open("/sessao/personagem?popout=inventario&token=<?=$fichat?>", "yyyyy", "width=480,height=360,resizable=no,toolbar=no,menubar=no,location=no,status=no");
            return false;
        })
        $('#card_pericias .popout').on('click', function () {
            window.open("/sessao/personagem?popout=pericias&token=<?=$fichat?>", "yyyyy", "width=480,height=360,resizable=no,toolbar=no,menubar=no,location=no,status=no");
            return false;
        })
        $('#card_habilidades .popout').on('click', function () {
            window.open("/sessao/personagem?popout=habilidades&token=<?=$fichat?>", "yyyyy", "width=480,height=360,resizable=no,toolbar=no,menubar=no,location=no,status=no");
            return false;
        })
        $('#card_proeficiencias .popout').on('click', function () {
            window.open("/sessao/personagem?popout=proeficiencias&token=<?=$fichat?>", "yyyyy", "width=480,height=360,resizable=no,toolbar=no,menubar=no,location=no,status=no");
            return false;
        })
        $('#card_rituais .popout').on('click', function () {
            window.open("/sessao/personagem?popout=rituais&token=<?=$fichat?>", "yyyyy", "width=480,height=360,resizable=no,toolbar=no,menubar=no,location=no,status=no");
            return false;
        })
        $('#card_rolar .popout').on('click', function () {
            window.open("/sessao/personagem?popout=rolar&token=<?=$fichat?>", "yyyyy", "width=480,height=360,resizable=no,toolbar=no,menubar=no,location=no,status=no");
            return false;
        })
        $('#pe input[type=checkbox]').change(function () {
            var checkboxes = $('#pe input:checkbox:checked').length;
            $.post({
                url: '?token=<?=$fichat?>',
                data: {status: 'pe', value: checkboxes},
            }).done(function () {
                $("#peatual").load("index.php?token=<?=$fichat?> #peatual");
            })
        });
        $("#verp").click(function () {
            $("#pericias .destreinado").toggle();
            $(this).children("i").children().addClass("fa-regular").toggleClass("fa-eye fa-eye-slash");
        });
        $("#vera").click(function () {
            $('#card_inventario .trocavision').toggle();
            $(this).children("i").children().addClass("fa-regular").toggleClass("fa-eye fa-eye-slash");
        });
    });
	<?php } else {?>
    $(document).ready(function () {
        $("#verp").click(function () {
            $("#pericias .destreinado").toggle();
            $(this).children("i").children().addClass("fa-regular").toggleClass("fa-eye fa-eye-slash");
        });
        $("#vera").click(function () {
            $('#card_inventario .trocavision').toggle();
            $(this).children("i").children().addClass("fa-regular").toggleClass("fa-eye fa-eye-slash");
        });
    });
	<?php
	}?>
</script>