<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;
    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank(message="Le prénom ne peut pas être vide")
     * @Assert\Regex(
     *     pattern="/^[a-zA-ZÀ-ÿ\s]*$/",
     *     message="Le nom ne peut contenir que des lettres et des espaces"
     * )
     */

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;
    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank(message="Le prénom ne peut pas être vide")
     * @Assert\Regex(
     *     pattern="/^[a-zA-ZÀ-ÿ\s]*$/",
     *     message="Le prénom ne peut contenir que des lettres et des espaces"
     * )
     */


    #[ORM\Column(length: 255)]
    private ?string $contenu = null;
    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank(message="La description ne peut pas être vide")
     * @Assert\Length(
     *      min=2,
     *      max=255,
     *      maxMessage="La contenu ne peut pas dépasser {{ limit }} caractères"
     * )
     */
    #[ORM\OneToOne(inversedBy: 'reponse', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Reclamation $id_reclamation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }
    public function getImgg(): ?string
    {
        return $this->imgg;
    }

    public function setImgg(?string $img): static
    {
        $this->img = $img;

        return $this;
    }
    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }
    public function getIdReclamation(): ?Reclamation
    {
        return $this->id_reclamation;
    }

    public function setIdReclamation(Reclamation $id_reclamation): static
    {
        $this->id_reclamation = $id_reclamation;

        return $this;
    }
}
