<div class="col-md-6" id="rodada">
    <div class="card h-100 w-100 bg-black border-light">
        <div class="card-body p-0">
            <div class="card-header text-center">
                <h3 class="font6 m-0">Iniciativas.</h3>
            </div>
            <div class="container-fluid p-0">
                <table class="table table-black text-white" id="iniciativa">
                    <input type="hidden" name="status" value="updtini">
                    <thead>
                    <tr>
                        <th>
                            <button type="button" class="btn btn-sm text-success" onclick="adicionariniciativa();">
                                <i class="fa-regular fa-square-plus"></i>
                            </button>
                        </th>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Iniciativa</th>
                        <th>Dano</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody class="ui-drag">
					<?php


					if ($a->num_rows) {
						foreach ($a as $i => $r) {
							$p = $i + 1;
							?>
                            <tr class="drag">
                                <input type="hidden" class="hidden" name="prioridade[]" value="<?= $p; ?>">
                                <input type="hidden" name="id[]" value="<?= $r["id"]; ?>">
                                <td class="p-0">
                                    <button type="button" class="btn up btn-sm text-info"><i
                                                class="fa-solid fa-chevrons-up"></i></button>
                                </td>
                                <td class="index p-0">
									<?= $p ?>
                                </td>
                                <td class="iniciativa p-0">
                                    <input type="text" style="min-width: 40px" autocomplete="off"
                                           class="bg-transparent text-white form-control border-0 form-control-sm"
                                           name="nome[]" readonly value="<?= $r["nome"]; ?>">
                                </td>
                                <td class="iniciativa p-0">
                                    <input type="number" style="max-width: 70px" autocomplete="off"
                                           class="bg-transparent text-white form-control border-0 form-control-sm"
                                           name="iniciativa[]" readonly value="<?= $r["iniciativa"]; ?>">
                                </td>
                                <td class="iniciativa p-0">
                                    <input type="number" style="max-width: 45px" autocomplete="off"
                                           class="bg-transparent text-white form-control border-0 form-control-sm"
                                           name="dano[]" readonly value="<?= $r["dano"]; ?>">
                                </td>
                                <td class="p-0">
                                    <div class="d-inline">
                                        <button type="button" class="btn down btn-sm text-info">
                                            <i class="fa-solid fa-chevrons-down"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm text-danger"
                                                onclick="deletariniciativa(<?= $r["id"] ?>)">
                                            <i class="fa-regular fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
							<?php $p++;
						}
					} else { ?>
                        <tr>
                            <td></td>
                            <td class="index"></td>
                            <td>Crie uma iniciativa</td>
                            <td>adicionando um personagem.</td>
                            <td></td>
                            <td></td>
                        </tr>
						<?php
					}
					?>
                    </tbody>
                </table>
                <div class="card-footer border-0">
                        <span class="text-info"><i
                                    class="fa-regular fa-circle-info"></i> Clique duas vezes para editar.</span>
                </div>
            </div>
        </div>
    </div>
</div>