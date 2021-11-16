
<div class="box-content">
	<h2><i class="fa fa-id-card-o" aria-hidden="true"></i> Produtos Cadastrados</h2>
	<div class="busca">
		<h4><i class="fa fa-search"></i> Realizar uma busca</h4>
		<form method="post">
			<input placeholder="Procure pelo nome do produto" type="text" name="busca">
			<input type="submit" name="acao" value="Buscar!">
		</form>
	</div><!--busca-->
	<?php 
		if(isset($_POST['atualizar'])){
			$quantidade = $_POST['quantidade'];
			$produto_id = $_POST['produto_id'];
			if($quantidade <= 0 ){
				Painel::alert('erro','Você não pode atualizar a quantidade para igual ou menor a 0!');
			}else{
				MySql::conectar()->exec("UPDATE `tb_admin.estoque` SET quantidade = $quantidade WHERE id = $produto_id");
				Painel::alert('sucesso','Você atualizou a quantidade do produto com ID: <b>'.$_POST['produto_id'].'</b>');
			}
			
		}
	?>
	<div class="boxes">
		<?php 

			$sql = MySql::conectar()->prepare("SELECT * FROM `tb_admin.estoque`");
			$sql->execute();
			$produtos = $sql->fetchAll();
			foreach($produtos as $key => $value){
			$imagemSingle = MySql::conectar()->prepare("SELECT * FROM `tb_admin.estoque_imagens` WHERE produto_id = $value[id] LIMIT 1");
			$imagemSingle->execute();
			$imagemSingle = $imagemSingle->fetch()['imagem'];
		?>
			<div class="box-single-wraper">
				<div style="border: 1px solid #ccc;">
				<div class="box-imgs" style="width:30%;float: left;">
					<img src="<?php echo INCLUDE_PATH_PAINEL?>uploads/<?php echo $imagemSingle?>"/>
				</div><!--box-imgs-->
				<div style="width: 70%;float: left;border: 0;" class="box-single">
					<div class="body-box">
						<p><b><i class="fa fa-pencil"></i> Nome do Produto:</b> <?php echo $value['nome'];?></p>
						<p><b><i class="fa fa-pencil"></i> Descrição do Produto:</b> <?php echo $value['descricao'];?></p>
						<p><b><i class="fa fa-pencil"></i> Largura:</b> <?php echo $value['largura'];?>cm</p>
						<p><b><i class="fa fa-pencil"></i> Altura:</b> <?php echo $value['altura'];?>cm</p>
						<p><b><i class="fa fa-pencil"></i> Comprimento:</b> <?php echo $value['comprimento'];?>cm</p>
						<p><b><i class="fa fa-pencil"></i> Peso:</b> <?php echo $value['peso'];?></p>

						<div style="padding: 8px 0;border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;" class="group-btn">
							<form method="post" style="margin: 0;">
								<label>Quantidade Atual:</label>
								<input type="number" name="quantidade" min="0" max="900" step="1" value="<?php echo $value['quantidade'];?>">
								<input type="hidden" name="produto_id" value="<?php echo $value['id'];?>">
								<input style="background: #0091ea;" type="submit" name="atualizar" value="Atualizar!">
							</form>
						</div><!--group-btn-->

						<div class="group-btn">
							<a class="btn delete" item_id="<?php echo $value['id'];?>" href="<?php echo INCLUDE_PATH_PAINEL ?>"><i class="fa fa-times"></i> Excluir</a>
							<a class="btn edit" href="<?php echo INCLUDE_PATH_PAINEL ?>editar-produto?id=<?php echo $value['id'];?>; ?>"><i class="fa fa-pencil"></i> Editar</a>
						</div><!--group-btn-->
					</div><!--body-box-->
				</div><!--box-single-->
				
				<div class="clear"></div>
				</div>
			</div><!--box-single-wraper-->
		<?php } ?>
	</div><!--boxes-->

</div><!--box-content-->