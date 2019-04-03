<?php

namespace App\Entity;

use App\Entity\Pret;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LivreRepository")
 * @ApiResource(
 *          attributes={
 *              "order"= {
 *                  "titre":"ASC"
 *               }
 *          },
 *          collectionOperations={
 *              "get_coll_role_adherent"={
 *                  "method"="GET",
 *                  "path" ="/adherent/livres",
 *                  "normalization_context"={
 *                      "groups"={"get_role_adherent"}
 *                  }
 *                },
 *                "get_coll_role_manager"={
 *                    "method"="GET",
 *                    "path" ="/manager/livres",
 *                    "access_control"="is_granted('ROLE_MANAGER')",
 *                    "access_control_message"=" Vous n'avez pas les droits d'accéder à cette ressource"
 *                  },
 *                  "post"={
 *                    "method"="POST",
 *                    "access_control"="is_granted('ROLE_MANAGER')",
 *                    "access_control_message"=" Vous n'avez pas les droits d'accéder à cette ressource"
 *                   }
 *            },
 *            itemOperations={
 *                "get_item_role_adherent"={
 *                  "method"="GET",
 *                  "path" ="/adherent/livre/{id}",
 *                  "normalization_context"={
 *                      "groups"={"get_role_adherent"}
 *                   }
 *                 },
 *                "get_item_role_manager"={
 *                    "method"="GET",
 *                    "path" ="/manager/livre/{id}",
 *                    "access_control"="is_granted('ROLE_MANAGER')",
 *                    "access_control_message"=" Vous n'avez pas les droits d'accéder à cette ressource"
 *                 },          
 *                "put_item_role_manager"={
 *                  "method"="PUT",
 *                  "path" ="/manager/livre/{id}",
 *                  "access_control"="is_granted('ROLE_MANAGER')",
 *                  "access_control_message"=" Vous n'avez pas les droits d'accéder à cette ressource",
 *                  "denormalization_context"= {
 *                      "groups"={"put_manager"}
 *                   }
 *                 },
 *                "put_item_role_admin"={
 *                 "method"="PUT",
 *                 "path" ="/admin/livre/{id}",
 *                 "access_control"="is_granted('ROLE_ADMIN')",
 *                 "access_control_message"=" Vous n'avez pas les droits d'accéder à cette ressource"
 *                                       },
 *                "delete"={
 *                 "method"="DELETE",
 *                 "path" ="/admin/livre/{id}",
 *                 "access_control"="is_granted('ROLE_ADMIN')",
 *                 "access_control_message"=" Vous n'avez pas les droits d'accéder à cette ressource"
 *                 }   
 *            }
 *                      
 * )
 * @ApiFilter(
 *      SearchFilter::class,
 *      properties={
 *          "titre": "ipartial",
 *             "auteur" : "exact",
 *              "genre" : "exact"
 *          }
 * )
 *  @ApiFilter(
 *      PropertyFilter::class,
 *      arguments={
 *          "parameterName": "properties",
 *          "overrideDefaultProperties" : false,
 *          "whitelist"= {
 *              "isbn",
 *              "titre",
 *              "prix"
 *              }
 *          }
 * )
 */
class Livre
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @groups ({"get_role_adherent","put_manager"})
     */
    private $isbn;

    /**
     * @ORM\Column(type="string", length=255)
     * @groups ({"get_role_adherent","put_manager"})
     */
    private $titre;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Genre", inversedBy="livres")
     * @ORM\JoinColumn(nullable=false)
     * @groups ({"get_role_adherent","put_manager"})
     */
    private $genre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Editeur", inversedBy="livres")
     * @ORM\JoinColumn(nullable=false)
     * @groups ({"get_role_adherent","put_manager"})
     */
    private $editeur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Auteur", inversedBy="livres")
     * @ORM\JoinColumn(nullable=false)
     * @groups ({"get_role_adherent","put_manager"})
     */
    private $auteur;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @groups ({"get_role_adherent","put_manager"})
     */
    private $annee;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"listGenreFull","listAuteurFull"})
     * @groups ({"get_role_adherent","put_manager"})
     */
    private $langue;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Pret", mappedBy="livre")
     */
    private $prets;

    public function __construct()
    {
        $this->prets = new ArrayCollection();
    }

    public function getId(): ? int
    {
        return $this->id;
    }

    public function getIsbn(): ? string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getTitre(): ? string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getPrix(): ? float
    {
        return $this->prix;
    }

    public function setPrix(? float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getGenre(): ? Genre
    {
        return $this->genre;
    }

    public function setGenre(? Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getEditeur(): ? Editeur
    {
        return $this->editeur;
    }

    public function setEditeur(? Editeur $editeur): self
    {
        $this->editeur = $editeur;

        return $this;
    }

    public function getAuteur(): ? Auteur
    {
        return $this->auteur;
    }

    public function setAuteur(? Auteur $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getAnnee(): ? int
    {
        return $this->annee;
    }

    public function setAnnee(? int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getLangue(): ? string
    {
        return $this->langue;
    }

    public function setLangue(? string $langue): self
    {
        $this->langue = $langue;

        return $this;
    }
    public function __toString()
    {
        return (string)$this->titre;
    }

    /**
     * @return Collection|Pret[]
     */
    public function getPrets(): Collection
    {
        return $this->prets;
    }

    public function addPrets(Pret $prets): self
    {
        if (!$this->prets->contains($prets)) {
            $this->prets[] = $prets;
            $prets->setLivre($this);
        }

        return $this;
    }

    public function removePrets(Pret $prets): self
    {
        if ($this->prets->contains($prets)) {
            $this->prets->removeElement($prets);
            // set the owning side to null (unless already changed)
            if ($prets->getLivre() === $this) {
                $prets->setLivre(null);
            }
        }

        return $this;
    }
}
