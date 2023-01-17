<?php 

    require_once 'process-key-api.php';
    require_once 'connection_ftp.php';

?>

<div class="wrap">

	<div class="tab-content">

		<div id="tab-3" class="tab-pane active">
			
            <form class="config_ap" method="post">

                <table class="wp-list-table widefat fixed striped pages">

                    <thead>
                        <th><h3><?= __( 'Administrador', 'iperp' ); ?></h3></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </thead>

                    <tbody>
                        
                        <tr>
                            <td>
                                <label for="email_admin"><?= __( 'Email', 'iperp' ); ?></label>
                            </td>

                            <td>  
                                <?php $email_admin = isset( $list[0]['email_admin'] ) ? $list[0]['email_admin'] : ''; ?>

                                <input type="email" value="<?= $email_admin ?>" name="email_admin" placeholder="<?= __( 'Ingrese Email del Administrador', 'iperp' ); ?>" required>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                            
                    </tbody>

                    <thead>
                        <th><h3><?= __( 'Credenciales API', 'iperp' ); ?></h3></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </thead>

                    <tbody id="the-list">
                        
                        <tr>
                            <td>
                                <label for="url_key_erp"><?= __( 'URL', 'iperp' ); ?></label>
                            </td>

                            <td>  
                                <?php $url_key = isset( $list[0]['url_key'] ) ? $list[0]['url_key'] : ''; ?>

                                <input type="text" value="<?= $url_key ?>" name="url_key_erp" placeholder="<?= __( 'Ingrese URL', 'iperp' ); ?>" required>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>
                                <label for="cliente_key_erp"><?= __( 'Clave del cliente', 'iperp' ); ?></label>
                            </td>

                            <td>  
                                <?php $key_client = isset( $list[0]['key_client'] ) ? $list[0]['key_client'] : ''; ?>

                                <input type="text" value="<?= $key_client ?>" name="cliente_key_erp" placeholder="<?= __( 'Ingrese clave del cliente', 'iperp' ); ?>" required>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>
                                <label for="private_key_erp"><?= __( 'Clave secreta de cliente', 'iperp' ); ?></label>
                            </td>
                        
                            <td>  
                                <?php $key_Secret = isset( $list[0]['key_Secret'] ) ? $list[0]['key_Secret'] : ''; ?>

                                <input type="text" value="<?= $key_Secret ?>" name="private_key_erp" placeholder="<?= __( 'Ingrese clave secreta', 'iperp' ); ?>" required>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                            
                    </tbody>

                    <thead>
                        <th><h3><?= __( 'Credenciales FTP', 'iperp' ); ?></h3></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </thead>

                    <tbody>
                        
                        <tr>
                            <td>
                                <label for="server_ftp"><?= __( 'Servidor', 'iperp' ); ?></label>
                            </td>

                            <td>  
                                <?php $server = isset( $list[0]['server_ftp'] ) ? $list[0]['server_ftp'] : ''; ?>

                                <input type="text" value="<?= $server ?>" name="server_ftp" placeholder="<?= __( 'Ingrese el Servidor FTP', 'iperp' ); ?>" required>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>
                                <label for="port_ftp"><?= __( 'Puerto', 'iperp' ); ?></label>
                            </td>

                            <td>  
                                <?php $port = isset( $list[0]['port_ftp'] ) ? $list[0]['port_ftp'] : ''; ?>

                                <select name="port_ftp" id="">
                                    <option value="21" <?php selected( $port, 21 ); ?> >21</option>
                                    <option value="22" <?php selected( $port, 22 ); ?> >22</option>
                                </select>
                            </td>
                            <td><?= __( 'Seleccione el Puerto FTP', 'iperp' ); ?></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>
                                <label for="user_ftp"><?= __( 'Usuario', 'iperp' ); ?></label>
                            </td>

                            <td>  
                                <?php $user = isset( $list[0]['user_ftp'] ) ? $list[0]['user_ftp'] : ''; ?>

                                <input type="text" value="<?= $user ?>" name="user_ftp" placeholder="<?= __( 'Ingrese el Usuario FTP', 'iperp' ); ?>" required>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>
                                <label for="user_ftp"><?= __( 'Ruta de archivos', 'iperp' ); ?></label>
                            </td>

                            <td>  
                                <?php $user = isset( $list[0]['file_path_ftp'] ) ? $list[0]['file_path_ftp'] : ''; ?>

                                <input type="text" value="<?= $user ?>" name="file_path_ftp" placeholder="<?= __( 'Ingrese la ruta de archivos', 'iperp' ); ?>" required>
                            </td>
                            <td><?= __( 'La ruta de la carpeta contenedora de archivos desde la raiz FTP. <br> Debe comenzar y terminar con "/". <br> ej: "/myfolder/" ', 'iperp' ); ?></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>
                                <label for="pass_ftp"><?= __( 'Contraseña', 'iperp' ); ?></label>
                            </td>
                        
                            <td>  
                                <?php $pass = isset( $list[0]['pass_ftp'] ) ? $list[0]['pass_ftp'] : ''; ?>

                                <input type="password" value="<?= $pass ?>" name="pass_ftp" placeholder="<?= __( 'Añada la contraseña FTP', 'iperp' ); ?>" required>
                            </td>
                            <td>
                                <?= $msg; ?>
                            </td>
                            <td></td>
                        </tr>
                        
                        <tr>
                            <td>
                                <?php if(empty($list)): ?>

                                    <button type="submit" class="page-title-action btn-primary_ap" name="btnConfApiKey" id="btnConfApiKey"><?= __( 'Guardar', 'iperp' ); ?></button>
                                <?php else: ?>

                                    <?php $id = $list[0]['IdconfKey']; ?>

                                    <input type="hidden" value="<?= $id ;?>" name="id_key_erp" >

                                    <button type="submit" class="page-title-action btn-primary_ap" name="btnConfApiKeyActualizar" id="btnConfApiKeyActualizar"><?= __( 'Actualizar', 'iperp' ); ?></button>

                                <?php endif;?>
                            </td>
                        </tr>
                            
                    </tbody>

                </table>

            </form>

		</div>

	</div>

</div>
