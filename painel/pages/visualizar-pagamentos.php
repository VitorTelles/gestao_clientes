
<div class="box-content">


	<?php 
	if(isset($_GET['email'])){
		//Queremos enviar um email para o cliente devedor ou atraso
		$parcela_id = (int)$_GET['parcela'];
		$cliente_id = (int)$_GET['email'];
		if(isset($_COOKIE['cliente_'.$cliente_id])){
			Painel::alert('erro','Você ja enviou um email cobrando esse cliente, aguarde mais um pouco!');
		}else{
			//Podemos enviar o email
			$sql = MySql::conectar()->prepare("SELECT * FROM `tb_admin.financeiro` WHERE id = $parcela_id");
			$sql->execute();
			$infoFinanceiro = $sql->fetch(); 

			$sql = MySql::conectar()->prepare("SELECT * FROM `tb_admin.clientes` WHERE id = $cliente_id");
			$sql->execute();
			$infoCliente = $sql->fetch();

			$corpoEmail = "Olá $infoCliente[nome], você está com um saldo pendente de $infoFinanceiro[valor] com o vencimento para $infoFinanceiro[vencimento]. Entre em contato conosco para quitar sua parcela.";
			$email = New Email('smtp.gmail.com','vitortelles120599@gmail.com','vitor@1999','Vitor Telles - Teste');
			$email->addAddress($infoCliente['email'],$infoCliente['nome']);
			$email->formatarEmail(array('assunto'=>'Cobrança','corpo'=>$corpoEmail));
			$email->enviarEmail();
			Painel::alert('sucesso','E-mail enviado com sucesso!');
			setcookie('cliente_'.$cliente_id,'true',time()+30,'/');
		}
	}
	?>

	<?php 
	if(isset($_GET['pago'])){
			$sql = MySql::conectar()->prepare("UPDATE `tb_admin.financeiro` SET status = 1 WHERE id = ?");
			$sql->execute(array($_GET['pago']));
			Painel::alert('sucesso','O(s) pagamento(s) foi quitado com sucesso!');
		}
	?>
<h2><i class="fa fa-id-card-o"></i> Pagamentos Pendentes</h2>
<div class="gerar-pdf">
	<a target="_blank" href="<?php echo INCLUDE_PATH_PAINEL ?>gerar-pdf.php?pagamento=pendentes">Gerar PDF</a>
</div>

	<div class="wraper-table">
	<table>
		<tr>
			<td>Nome do pagamento</td>
			<td>Cliente</td>
			<td>Valor</td>
			<td>Vencimento</td>
			<td>Enviar E-mail</td>
			<td>Marcar como pago</td>
		</tr>
	<?php 

		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_admin.financeiro` WHERE status = 0 ORDER BY vencimento ASC");
		$sql->execute();
		$pendentes = $sql->fetchAll();
		foreach ($pendentes as $key => $value){
			$clienteNome = MySql::conectar()->prepare("SELECT `nome`,`id` FROM `tb_admin.clientes` WHERE id = $value[cliente_id]");
			$clienteNome->execute();
			$info = $clienteNome->fetch();
			$clienteNome = $info['nome'];
			$idCliente = $info['id'];
			$style = "";
			if(strtotime(date('Y-m-d')) >= strtotime($value['vencimento'])){
				$style = 'style = "background-color:#ff7070;font-weight:bold;"';
			}
		?>
		<tr <?php echo $style; ?>>
			<td><?php echo $value['nome']; ?></td>
			<td><?php echo $clienteNome; ?></td>
			<td><?php echo $value['valor']; ?></td>
			<td><?php echo date('d/m/Y',strtotime($value['vencimento'])); ?></td>
			<td><a class="btn edit" href="<?php echo INCLUDE_PATH_PAINEL ?>visualizar-pagamentos?email=<?php echo $info['id']; ?>&parcela=<?php echo $value['id']; ?>"><i class="fa fa-envelope"></i> E-mail</a></td>
			<td><a style="background: #00bfa5;" class="btn" href="<?php echo INCLUDE_PATH_PAINEL?>visualizar-pagamentos?pago=<?php echo $value['id']?>"><i class="fa fa-check"></i> Pago</a></td>
		</tr>

	<?php } ?>
	


	</table>
	</div>

	<h2><i class="fa fa-id-card-o"></i> Pagamentos Concluídos</h2>

	<div class="gerar-pdf">
		<a href="<?php echo INCLUDE_PATH_PAINEL ?>gerar-pdf.php?pagamento=concluidos" target="_blank">Gerar PDF</a>
	</div>

	<div class="wraper-table">
	<table>
		<tr>
			<td>Nome do pagamento</td>
			<td>Cliente</td>
			<td>Valor</td>
			<td>Vencimento</td>
			<td>#</td>
		</tr>
	<?php 

		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_admin.financeiro` WHERE status = 1 ORDER BY vencimento ASC ");
		$sql->execute();
		$pendentes = $sql->fetchAll();
		foreach ($pendentes as $key => $value){
			$clienteNome = MySql::conectar()->prepare("SELECT `nome` FROM `tb_admin.clientes` WHERE id = $value[cliente_id]");
			$clienteNome->execute();
			$clienteNome = $clienteNome->fetch()['nome'];
		?>
		<tr>
			<td><?php echo $value['nome']; ?></td>
			<td><?php echo $clienteNome; ?></td>
			<td><?php echo $value['valor']; ?></td>
			<td><?php echo date('d/m/Y',strtotime($value['vencimento'])); ?></td>
		</tr>

	<?php } ?>

	</table>
	</div>



</div><!--box-content-->