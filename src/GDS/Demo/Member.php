<?php
/**
 * Membre pour GDS
 *
 * @author Yvon Benahita
 */
namespace GDS\Demo;
use GDS\Schema;
use GDS\Store;

class Membre
{
	 /**
     * Instance du magasin GDS
     *
     * @var Store|null
     */
    private $obj_store = NULL;

    /**
     * Créez un schéma pour les entrées du formulaire 
     *
     * 'posted' est 'heure de la date d'entrée des valeur 
     *
     * @return Schema
     */
    private function makeSchema()
    {
        return (new Schema('Espace_membre'))
            ->addString('nom', FALSE)
            ->addString('mail', FALSE)
            ->addString('mdp', FALSE)
        ;
    }

     /**
     * Configuration et retourner un magasin
     *
     * @return Store
     */
    private function getStore()
    {
        if(NULL === $this->obj_store) {
            $this->obj_store = new Store($this->makeSchema());
        }
        return $this->obj_store;
    }

     /**
     * Insèrez l'entité dans la Base
     *
     * @param $str_nom
     * @param $str_mail
     * @param $str_mdp
     */
    public function createPost($str_nom, $str_mail, $str_mdp)
    {
        $obj_store = $this->getStore();
        $obj_store->upsert($obj_store->createEntity([
            'nom' => $str_nom,
            'mail' => $str_mail,
            'mdp' => $str_mdp,
        ]));
    }
}