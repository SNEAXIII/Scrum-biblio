<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table("emprunt")]
class Emprunt
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;
    #[ORM\Column(length: 12)]
    private ?string $numeroEmprunt;
    #[ORM\Column(type: 'date')]
    private DateTime $dateEmprunt;
    #[ORM\Column(type: 'date')]
    private DateTime $dateRetourEstime;
    #[ORM\Column(type: 'date',nullable: true)]
    private ?DateTime $dateRetourEffectif;
    #[ORM\ManyToOne(targetEntity: Adherent::class)]
    #[ORM\JoinColumn(name: 'adherent_id', referencedColumnName: 'id')]
    private Adherent $adherent;
    #[ORM\ManyToOne(targetEntity: Media::class)]
    #[ORM\JoinColumn(name: 'media_id', referencedColumnName: 'id')]
    private Media $media;

    public function __construct()
    {
    }

    public function isEnCours(): bool
    {
        return !isset($this -> dateRetourEffectif);
    }

    public function isEnRetard(): bool
    {
        $isDepasse = $this -> dateRetourEstime < new DateTime();
        return ($this -> isEnCours() && $isDepasse);
    }

    public function getDateRetourEstime(): DateTime
    {
        return $this -> dateRetourEstime;
    }

    public function setDateRetourEstime(DateTime $dateRetourEstime): void
    {
        $this -> dateRetourEstime = $dateRetourEstime;
    }

    public function getDateRetourEffectif(): ?DateTime
    {
        return $this -> dateRetourEffectif;
    }

    public function setDateRetourEffectif(?DateTime $dateRetourEffectif): void
    {
        $this -> dateRetourEffectif = $dateRetourEffectif;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this -> id;
    }

    /**
     * @return string|null
     */
    public function getNumeroEmprunt(): ?string
    {
        return $this -> numeroEmprunt;
    }

    /**
     * @param string|null $numeroEmprunt
     */
    public function setNumeroEmprunt(?string $numeroEmprunt): void
    {
        $this -> numeroEmprunt = $numeroEmprunt;
    }

    /**
     * @return DateTime
     */
    public function getDateEmprunt(): DateTime
    {
        return $this -> dateEmprunt;
    }

    /**
     * @param DateTime $dateEmprunt
     */
    public function setDateEmprunt(DateTime $dateEmprunt): void
    {
        $this -> dateEmprunt = $dateEmprunt;
    }

    /**
     * @return Adherent
     */
    public function getAdherent(): Adherent
    {
        return $this -> adherent;
    }

    /**
     * @param Adherent $adherent
     */
    public function setAdherent(Adherent $adherent): void
    {
        $this -> adherent = $adherent;
    }

    /**
     * @return Media
     */
    public function getMedia(): Media
    {
        return $this -> media;
    }

    /**
     * @param Media $media
     */
    public function setMedia(Media $media): void
    {
        $this -> media = $media;
    }
}